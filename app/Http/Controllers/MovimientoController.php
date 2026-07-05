<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CRUD de movimientos financieros (ingresos y egresos).
 */
class MovimientoController extends Controller
{
    /**
     * Lista movimientos con filtros por búsqueda, tipo y fecha.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Transaction::where('user_id', $userId);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('transaction_date', $date);
        }

        $transactions = $query->orderByDesc('transaction_date')->paginate(10);

        $monthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'credito')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $monthExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'debito')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $netBalance = $monthIncome - $monthExpenses;

        return view('movimientos', compact(
            'transactions', 'monthIncome', 'monthExpenses', 'netBalance'
        ));
    }

    /**
     * Registra un nuevo movimiento y actualiza el saldo acumulado.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:credito,debito'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:100'],
        ]);

        $userId = Auth::id();

        $lastBalance = Transaction::where('user_id', $userId)
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $amount = $validated['type'] === 'credito' ? $validated['amount'] : -$validated['amount'];
        $newBalance = $lastBalance + $amount;

        $transaction = Transaction::create([
            'user_id' => $userId,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'balance' => $newBalance,
            'transaction_date' => $validated['transaction_date'],
            'category' => $validated['category'] ?? null,
            'reference' => $validated['reference'] ?? null,
        ]);

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'created',
            'entity_type' => 'transaction',
            'entity_id' => $transaction->id,
            'description' => "Movimiento creado: {$transaction->description} - \${$transaction->amount}",
        ]);

        return redirect()->route('movimientos')->with('success', 'Movimiento registrado exitosamente.');
    }

    /**
     * Muestra el detalle de un movimiento específico.
     */
    public function show(Transaction $movimiento): View
    {
        $this->authorize('view', $movimiento);

        return view('movimientos-show', compact('movimiento'));
    }

    /**
     * Devuelve datos del movimiento en JSON para edición vía AJAX.
     */
    public function edit(Transaction $movimiento): JsonResponse
    {
        $this->authorize('view', $movimiento);

        return response()->json($movimiento);
    }

    /**
     * Actualiza un movimiento y reajusta los saldos de movimientos posteriores.
     */
    public function update(Request $request, Transaction $movimiento): RedirectResponse
    {
        $this->authorize('update', $movimiento);

        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:credito,debito'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:100'],
        ]);

        $userId = Auth::id();
        $oldValues = $movimiento->toArray();

        $oldSigned = $movimiento->type === 'credito' ? $movimiento->amount : -$movimiento->amount;
        $newSigned = $validated['type'] === 'credito' ? $validated['amount'] : -$validated['amount'];
        $delta = $newSigned - $oldSigned;

        $prevBalance = Transaction::where('user_id', $userId)
            ->where('id', '<', $movimiento->id)
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $newBalance = $prevBalance + $newSigned;

        $movimiento->update([
            'description' => $validated['description'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'balance' => $newBalance,
            'transaction_date' => $validated['transaction_date'],
            'category' => $validated['category'] ?? null,
            'reference' => $validated['reference'] ?? null,
        ]);

        if ($delta !== 0.0) {
            Transaction::where('user_id', $userId)
                ->where('id', '>', $movimiento->id)
                ->increment('balance', $delta);
        }

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'updated',
            'entity_type' => 'transaction',
            'entity_id' => $movimiento->id,
            'description' => "Movimiento editado: {$movimiento->description}",
            'old_values' => $oldValues,
            'new_values' => $movimiento->toArray(),
        ]);

        return redirect()->route('movimientos')->with('success', 'Movimiento actualizado exitosamente.');
    }

    /**
     * Elimina un movimiento y registra la acción en la auditoría.
     */
    public function destroy(Transaction $movimiento): RedirectResponse
    {
        $this->authorize('delete', $movimiento);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'entity_type' => 'transaction',
            'entity_id' => $movimiento->id,
            'description' => "Movimiento eliminado: {$movimiento->description}",
            'old_values' => $movimiento->toArray(),
        ]);

        $movimiento->delete();

        return redirect()->route('movimientos')->with('success', 'Movimiento eliminado exitosamente.');
    }
}
