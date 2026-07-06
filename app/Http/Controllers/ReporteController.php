<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;

/**
 * Reportes financieros: resumen, gastos por categoría y exportación a Excel.
 */
class ReporteController extends Controller
{
    /**
     * Prepara datos de resumen, gastos por categoría y flujo mensual para la vista.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $request->validate([
            'range' => ['nullable', 'in:7d,30d,90d,1y,all,custom'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->sum('amount');

        $totalExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;

        $expensesByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->selectRaw('COALESCE(category, "Otros") as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $incomeByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->selectRaw('COALESCE(category, "Sin categoría") as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', transaction_date)"
            : "DATE_FORMAT(transaction_date, '%Y-%m')";

        $monthlyData = Transaction::where('user_id', $userId)
            ->selectRaw("{$dateExpr} as month, SUM(CASE WHEN type = 'credito' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'debito' THEN amount ELSE 0 END) as expenses")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $range = $request->get('range', '1y');
        $customFrom = $request->get('from');
        $customTo = $request->get('to');

        $startDate = match ($range) {
            '7d'   => now()->subDays(7)->startOfDay(),
            '30d'  => now()->subDays(30)->startOfDay(),
            '90d'  => now()->subDays(90)->startOfDay(),
            '1y'   => now()->subYear()->startOfDay(),
            'all'  => null,
            'custom' => $customFrom ? Carbon::parse($customFrom)->startOfDay() : now()->subDays(30)->startOfDay(),
            default => now()->subYear()->startOfDay(),
        };

        $endDate = match ($range) {
            'custom' => $customTo ? Carbon::parse($customTo)->endOfDay() : now()->endOfDay(),
            default  => now()->endOfDay(),
        };

        $chartQuery = Transaction::where('user_id', $userId)
            ->selectRaw('DATE(transaction_date) as date, SUM(CASE WHEN type = "credito" THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = "debito" THEN amount ELSE 0 END) as expenses');

        if ($startDate) {
            $chartQuery->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $chartQuery->where('transaction_date', '<=', $endDate);
        }

        $chartData = $chartQuery
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($row) => (object)[
                'date'     => Carbon::parse($row->date),
                'income'   => (float)$row->income,
                'expenses' => (float)$row->expenses,
                'net'      => (float)$row->income - (float)$row->expenses,
            ]);

        $rangeLabel = match ($range) {
            '7d'     => 'últimos 7 días',
            '30d'    => 'últimos 30 días',
            '90d'    => 'últimos 90 días',
            '1y'     => 'último año',
            'all'    => 'todo el historial',
            'custom' => ($customFrom && $customTo)
                ? Carbon::parse($customFrom)->format('d/m/Y') . ' - ' . Carbon::parse($customTo)->format('d/m/Y')
                : 'personalizado',
            default => 'último año',
        };

        return view('reportes', compact(
            'totalIncome', 'totalExpenses', 'netProfit',
            'expensesByCategory', 'incomeByCategory', 'monthlyData',
            'chartData', 'range', 'rangeLabel',
            'customFrom', 'customTo'
        ));
    }

    /**
     * Genera y descarga un archivo XLSX con el reporte financiero completo.
     */
    public function exportCsv()
    {
        $userId = Auth::id();
        $now = now();

        $totalIncome = (float) Transaction::where('user_id', $userId)->where('type', 'credito')->sum('amount');
        $totalExpenses = (float) Transaction::where('user_id', $userId)->where('type', 'debito')->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', transaction_date)"
            : "DATE_FORMAT(transaction_date, '%Y-%m')";

        $monthlyData = Transaction::where('user_id', $userId)
            ->selectRaw("{$dateExpr} as month, SUM(CASE WHEN type = 'credito' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'debito' THEN amount ELSE 0 END) as expenses")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $expensesByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->selectRaw('COALESCE(category, "Otros") as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $meses = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre',
        ];

        $darkBlue = '002060';
        $darkBlueBg = 'FF002060';
        $white = 'FFFFFF';
        $lightGrayBg = 'FFF2F2F2';
        $green = '00B050';
        $red = 'C00000';
        $darkGray = '404040';

        $styleTitle = (new Style())
            ->withFontBold(true)->withFontSize(16)
            ->withFontColor($darkBlue)->withFontName('Calibri');

        $styleSubtitle = (new Style())
            ->withFontSize(11)->withFontColor($darkGray)
            ->withFontName('Calibri')->withFontItalic(true);

        $styleSection = (new Style())
            ->withFontBold(true)->withFontSize(13)
            ->withFontColor($darkBlue)->withFontName('Calibri');

        $styleTableHeader = (new Style())
            ->withFontBold(true)->withFontSize(11)
            ->withFontColor($white)->withBackgroundColor($darkBlueBg)
            ->withFontName('Calibri')->withCellAlignment(CellAlignment::CENTER);

        $styleLabel = (new Style())
            ->withFontBold(true)->withFontSize(11)
            ->withFontName('Calibri')->withFontColor($darkGray);

        $styleValue = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT);

        $styleValuePositive = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withFontColor($green)
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT);

        $styleValueNegative = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withFontColor($red)
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT);

        $styleTotalLabel = (new Style())
            ->withFontBold(true)->withFontSize(11)
            ->withFontName('Calibri')->withFontColor($darkGray);

        $styleTotalValue = (new Style())
            ->withFontBold(true)->withFontSize(11)
            ->withFontName('Calibri')
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT);

        $styleCategoryRow = (new Style())
            ->withFontSize(11)->withFontName('Calibri');

        $styleCategoryRowAlt = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withBackgroundColor($lightGrayBg);

        $styleCategoryValue = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT);

        $styleCategoryValueAlt = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withFormat('#,##0.00')->withCellAlignment(CellAlignment::RIGHT)
            ->withBackgroundColor($lightGrayBg);

        $styleMonth = (new Style())
            ->withFontSize(11)->withFontName('Calibri');

        $styleMonthAlt = (new Style())
            ->withFontSize(11)->withFontName('Calibri')
            ->withBackgroundColor($lightGrayBg);

        $styleMonthTotal = (new Style())
            ->withFontBold(true)->withFontSize(11)
            ->withFontName('Calibri');

        $path = tempnam(sys_get_temp_dir(), 'reporte') . '.xlsx';

        $options = new Options();
        $options->setColumnWidth(9, 1);
        $options->setColumnWidth(42, 2);
        $options->setColumnWidth(22, 3);
        $options->setColumnWidth(22, 4);

        $writer = new Writer($options);
        $writer->openToFile($path);
        $writer->getCurrentSheet()->setName('ContaFlow');

        $headerColumns = [0 => $styleTableHeader, 1 => $styleTableHeader];
        $headerColumnsFlujo = [0 => $styleTableHeader, 1 => $styleTableHeader, 2 => $styleTableHeader, 3 => $styleTableHeader];

        $writer->addRow(Row::fromValuesWithStyle(
            ['Reporte Financiero — ContaFlow'],
            $styleTitle
        ));
        $writer->addRow(Row::fromValuesWithStyle(
            ['Generado el ' . $now->isoFormat('D [de] MMMM [de] YYYY')],
            $styleSubtitle
        ));

        // ==============================
        //  RESUMEN EJECUTIVO
        // ==============================
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValuesWithStyle(
            ['Resumen Ejecutivo'],
            $styleSection
        ));
        $writer->addRow(Row::fromValuesWithStyles(
            ['Cuenta', 'Monto'],
            $headerColumns
        ));

        $pairs = [
            ['Ingresos Totales', $totalIncome, $styleValuePositive],
            ['Egresos Totales', $totalExpenses, $styleValueNegative],
            ['Utilidad Neta', $netProfit, $netProfit >= 0 ? $styleValuePositive : $styleValueNegative],
        ];

        foreach ($pairs as [$label, $value, $valueStyle]) {
            $writer->addRow(Row::fromValuesWithStyles(
                [$label, $value],
                [0 => $styleLabel, 1 => $valueStyle]
            ));
        }

        // ==============================
        //  FLUJO DE CAJA MENSUAL
        // ==============================
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValuesWithStyle(
            ['Flujo de Caja Mensual'],
            $styleSection
        ));
        $writer->addRow(Row::fromValuesWithStyles(
            ['Mes', 'Ingresos', 'Egresos', 'Neto'],
            $headerColumnsFlujo
        ));

        $totalIncomeMonthly = 0.0;
        $totalExpensesMonthly = 0.0;
        $totalNetMonthly = 0.0;

        foreach ($monthlyData as $i => $row) {
            $parts = explode('-', $row->month);
            $monthLabel = ($meses[$parts[1] ?? ''] ?? $row->month) . ' ' . ($parts[0] ?? '');
            $income = (float) $row->income;
            $expenses = (float) $row->expenses;
            $net = $income - $expenses;
            $totalIncomeMonthly += $income;
            $totalExpensesMonthly += $expenses;
            $totalNetMonthly += $net;

            $isAlt = $i % 2 === 1;
            $writer->addRow(Row::fromValuesWithStyles(
                [$monthLabel, $income, $expenses, $net],
                [
                    0 => $isAlt ? $styleMonthAlt : $styleMonth,
                    1 => $isAlt ? $styleValuePositive : $styleValue,
                    2 => $isAlt ? $styleValueNegative : $styleValue,
                    3 => $net >= 0
                        ? ($isAlt ? $styleValuePositive : $styleValue)
                        : ($isAlt ? $styleValueNegative : $styleValue),
                ]
            ));
        }

        $writer->addRow(Row::fromValuesWithStyles(
            ['TOTAL', $totalIncomeMonthly, $totalExpensesMonthly, $totalNetMonthly],
            [
                0 => $styleTotalLabel,
                1 => $styleTotalValue,
                2 => $styleTotalValue,
                3 => $styleTotalValue,
            ]
        ));

        // ==============================
        //  GASTOS POR CATEGORÍA
        // ==============================
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValuesWithStyle(
            ['Gastos por Categoría'],
            $styleSection
        ));
        $writer->addRow(Row::fromValuesWithStyles(
            ['Categoría', 'Total'],
            $headerColumns
        ));

        $totalCategoryExpenses = 0.0;
        foreach ($expensesByCategory as $i => $row) {
            $isAlt = $i % 2 === 1;
            $total = (float) $row->total;
            $totalCategoryExpenses += $total;
            $writer->addRow(Row::fromValuesWithStyles(
                [$row->category, $total],
                [
                    0 => $isAlt ? $styleCategoryRowAlt : $styleCategoryRow,
                    1 => $isAlt ? $styleCategoryValueAlt : $styleCategoryValue,
                ]
            ));
        }

        $writer->addRow(Row::fromValuesWithStyles(
            ['TOTAL', $totalCategoryExpenses],
            [0 => $styleTotalLabel, 1 => $styleTotalValue]
        ));

        $writer->close();

        $filename = 'reporte-financiero-' . $now->format('Y-m-d') . '.xlsx';

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
