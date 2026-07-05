<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Panel principal — resumen financiero, gráficos y movimientos recientes.
 */
class DashboardController extends Controller
{
    /**
     * Renderiza el dashboard con balance, ingresos/gastos del mes,
     * gráfico por rango de fechas y gastos por categoría.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $range = $request->get('range', '30d');
        $customFrom = $request->get('from');
        $customTo = $request->get('to');

        $startDate = match ($range) {
            '7d'   => now()->subDays(7)->startOfDay(),
            '90d'  => now()->subDays(90)->startOfDay(),
            '1y'   => now()->subYear()->startOfDay(),
            'all'  => null,
            'custom' => $customFrom ? Carbon::parse($customFrom)->startOfDay() : now()->subDays(30)->startOfDay(),
            default => now()->subDays(30)->startOfDay(),
        };

        $endDate = match ($range) {
            'custom' => $customTo ? Carbon::parse($customTo)->endOfDay() : now()->endOfDay(),
            default  => now()->endOfDay(),
        };

        $currentBalance = Transaction::where('user_id', $userId)
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $monthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $monthExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

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

        $categoryExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->selectRaw('COALESCE(category, "Otros") as category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $rangeLabel = match ($range) {
            '7d'     => 'últimos 7 días',
            '30d'    => 'últimos 30 días',
            '90d'    => 'últimos 90 días',
            '1y'     => 'último año',
            'all'    => 'todo el historial',
            'custom' => ($customFrom && $customTo)
                ? Carbon::parse($customFrom)->format('d/m/Y') . ' - ' . Carbon::parse($customTo)->format('d/m/Y')
                : 'personalizado',
            default => 'últimos 30 días',
        };

        return view('dashboard', compact(
            'currentBalance', 'monthIncome', 'monthExpenses',
            'recentTransactions', 'chartData',
            'categoryExpenses', 'range', 'rangeLabel',
            'customFrom', 'customTo'
        ));
    }
}
