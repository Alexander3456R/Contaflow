<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Consulta del registro de auditoría de acciones del usuario.
 */
class TrazabilidadController extends Controller
{
    /**
     * Lista eventos de auditoría con filtros por búsqueda y fecha.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->where('user_id', Auth::id());

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('entity_type', 'like', "%{$search}%")
                  ->orWhere('entity_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->latest()->paginate(10);

        return view('trazabilidad', compact('logs'));
    }
}
