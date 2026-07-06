<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
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
    public function index(): View
    {
        $userId = Auth::id();

        $currentBalance = Transaction::where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $monthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $monthExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $recentTransactions = Transaction::where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $prevMonth = now()->subMonth();
        $prevMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->whereMonth('transaction_date', $prevMonth->month)
            ->whereYear('transaction_date', $prevMonth->year)
            ->sum('amount');

        $prevMonthExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->whereMonth('transaction_date', $prevMonth->month)
            ->whereYear('transaction_date', $prevMonth->year)
            ->sum('amount');

        $prevPrevMonth = now()->subMonths(2);
        $prevPrevIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->whereMonth('transaction_date', $prevPrevMonth->month)
            ->whereYear('transaction_date', $prevPrevMonth->year)
            ->sum('amount');
        $prevPrevExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->whereMonth('transaction_date', $prevPrevMonth->month)
            ->whereYear('transaction_date', $prevPrevMonth->year)
            ->sum('amount');

        return view('dashboard', compact(
            'currentBalance', 'monthIncome', 'monthExpenses',
            'prevMonthIncome', 'prevMonthExpenses',
            'prevPrevIncome', 'prevPrevExpenses',
            'recentTransactions'
        ));
    }
}
