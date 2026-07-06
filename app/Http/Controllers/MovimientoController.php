<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CRUD de movimientos financieros (ingresos y egresos).
 */
class MovimientoController extends Controller
{
    /**
     * Query de transacciones cronológicamente anteriores a una dada.
     * Orden: transaction_date ASC → created_at ASC → id ASC.
     */
    private function predecessorQuery(int $userId, string|Carbon $date, string|Carbon $createdAt, int $id)
    {
        return Transaction::where('user_id', $userId)
            ->where(function ($q) use ($date, $createdAt, $id) {
                $q->where('transaction_date', '<', $date)
                  ->orWhere(function ($q) use ($date, $createdAt, $id) {
                      $q->where('transaction_date', '=', $date)
                        ->where(function ($q) use ($createdAt, $id) {
                            $q->where('created_at', '<', $createdAt)
                              ->orWhere(function ($q) use ($createdAt, $id) {
                                  $q->where('created_at', '=', $createdAt)
                                    ->where('id', '<', $id);
                              });
                        });
                  });
            })
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    /**
     * Query de transacciones cronológicamente posteriores a una dada.
     */
    private function successorQuery(int $userId, string|Carbon $date, string|Carbon $createdAt, int $id)
    {
        return Transaction::where('user_id', $userId)
            ->where(function ($q) use ($date, $createdAt, $id) {
                $q->where('transaction_date', '>', $date)
                  ->orWhere(function ($q) use ($date, $createdAt, $id) {
                      $q->where('transaction_date', '=', $date)
                        ->where(function ($q) use ($createdAt, $id) {
                            $q->where('created_at', '>', $createdAt)
                              ->orWhere(function ($q) use ($createdAt, $id) {
                                  $q->where('created_at', '=', $createdAt)
                                    ->where('id', '>', $id);
                              });
                        });
                  });
            });
    }

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

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->paginate(10);

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

        $netBalance = $monthIncome - $monthExpenses;

        return view('movimientos', compact(
            'transactions', 'monthIncome', 'monthExpenses', 'netBalance'
        ));
    }

    /**
     * Registra un nuevo movimiento y actualiza el saldo acumulado
     * usando orden cronológico (transaction_date → created_at → id).
     * Las transacciones posteriores se incrementan automáticamente.
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

        // El nuevo registro tiene created_at = now(), por lo que siempre es
        // el último en su misma fecha. El predecesor es el último con
        // transaction_date <= la nueva fecha.
        $prevBalance = Transaction::where('user_id', $userId)
            ->where('transaction_date', '<=', $validated['transaction_date'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $signedAmount = $validated['type'] === 'credito' ? (float) $validated['amount'] : -(float) $validated['amount'];
        $newBalance = $prevBalance + $signedAmount;

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

        // Incrementar todas las transacciones con fecha posterior
        if ($signedAmount !== 0.0) {
            Transaction::where('user_id', $userId)
                ->where('transaction_date', '>', $validated['transaction_date'])
                ->increment('balance', $signedAmount);
        }

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
     * Actualiza un movimiento y reajusta los saldos de movimientos posteriores
     * usando orden cronológico (transaction_date → created_at → id).
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

        $oldSigned = $movimiento->type === 'credito' ? (float) $movimiento->amount : -(float) $movimiento->amount;
        $newSigned = $validated['type'] === 'credito' ? (float) $validated['amount'] : -(float) $validated['amount'];
        $delta = $newSigned - $oldSigned;

        // Predecesor cronológico (transaction_date, created_at, id)
        $predecessorBalance = $this->predecessorQuery(
            $userId, $movimiento->transaction_date, $movimiento->created_at, $movimiento->id
        )->value('balance') ?? 0;

        $newBalance = $predecessorBalance + $newSigned;

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
            $this->successorQuery(
                $userId, $movimiento->transaction_date, $movimiento->created_at, $movimiento->id
            )->increment('balance', $delta);
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
     * Elimina un movimiento, recalcula balances posteriores y registra en auditoría.
     */
    public function destroy(Transaction $movimiento): RedirectResponse
    {
        $this->authorize('delete', $movimiento);

        $userId = Auth::id();
        $signedAmount = $movimiento->type === 'credito' ? (float) $movimiento->amount : -(float) $movimiento->amount;

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'deleted',
            'entity_type' => 'transaction',
            'entity_id' => $movimiento->id,
            'description' => "Movimiento eliminado: {$movimiento->description}",
            'old_values' => $movimiento->toArray(),
        ]);

        $movimiento->delete();

        // Restar el impacto del movimiento eliminado de todos los posteriores
        if ($signedAmount !== 0.0) {
            $this->successorQuery(
                $userId, $movimiento->transaction_date, $movimiento->created_at, $movimiento->id
            )->decrement('balance', $signedAmount);
        }

        return redirect()->route('movimientos')->with('success', 'Movimiento eliminado exitosamente.');
    }
}
