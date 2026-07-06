@extends('layouts.app')

@section('title', 'ContaFlow - Movimientos')
@section('page-title')
<span class="material-symbols-outlined">swap_horiz</span> Movimientos
@endsection

@section('content')
<div class="p-6 md:p-8 space-y-6">
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
      <h3 class="font-headline-sm text-headline-sm text-on-surface">Historial de Transacciones</h3>
      <p class="text-on-surface-variant text-body-md">Gestione y supervise todos los registros financieros entrantes y salientes.</p>
    </div>
    <button id="createMovBtn" class="bg-primary text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
      <span class="material-symbols-outlined">add</span> Nuevo Movimiento
    </button>
  </div>
  <form method="GET" action="{{ route('movimientos') }}">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-outline-variant flex flex-col lg:flex-row gap-4 items-center">
      <div class="w-full lg:w-1/3 relative">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="Buscar por descripción o ID..." type="text"/>
      </div>
      <div class="flex flex-wrap items-center gap-3 w-full lg:w-2/3 lg:justify-end">
        <div class="flex items-center gap-2 bg-surface-container-low px-3 py-2 border border-outline-variant rounded-lg">
          <span class="material-symbols-outlined text-sm">calendar_today</span>
          <input name="date" value="{{ request('date') }}" class="bg-transparent border-none p-0 text-sm focus:ring-0" type="date"/>
        </div>
        <select name="type" class="bg-surface-container-low px-4 py-2 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          <option value="" {{ request('type') == '' ? 'selected' : '' }}>Todos los tipos</option>
          <option value="credito" {{ request('type') == 'credito' ? 'selected' : '' }}>Créditos</option>
          <option value="debito" {{ request('type') == 'debito' ? 'selected' : '' }}>Débitos</option>
        </select>
        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-opacity"><span class="material-symbols-outlined">filter_list</span><span>Filtrar</span></button>
      </div>
    </div>
  </form>
  {{-- Tabla de transacciones con filtros de búsqueda, tipo y fecha --}}
  <div class="bg-white rounded-xl shadow-md border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead><tr class="bg-surface-container-low border-b border-outline-variant">
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Fecha</th>
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Descripción</th>
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tipo</th>
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Monto</th>
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Saldo</th>
          <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center">Acciones</th>
        </tr></thead>
        <tbody class="divide-y divide-outline-variant">
          @forelse($transactions as $t)
          <tr class="hover:bg-surface-container-low transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-numeric-md font-numeric-md">{{ $t->transaction_date->format('d M, Y') }}</td>
            <td class="px-6 py-4">
              <div class="flex flex-col">
                <span class="font-bold text-on-surface">{{ $t->description }}</span>
                @if($t->category)
                <span class="text-xs text-on-surface-variant">{{ $t->category }}</span>
                @endif
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->type === 'credito' ? 'bg-tertiary-fixed text-on-tertiary-fixed-variant' : 'bg-error-container text-on-error-container' }} inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">{{ $t->type === 'credito' ? 'arrow_upward' : 'arrow_downward' }}</span>
                {{ ucfirst($t->type) }}
              </span>
            </td>
            <td class="px-6 py-4 text-right font-bold {{ $t->type === 'credito' ? 'text-tertiary' : 'text-error' }}">
              {{ $t->type === 'credito' ? '+' : '-' }}${{ number_format($t->amount, 2) }}
            </td>
            <td class="px-6 py-4 font-numeric-md">${{ number_format($t->balance, 2) }}</td>
            <td class="px-6 py-4">
              <div class="flex justify-center gap-1">
                <a href="{{ route('movimientos.show', $t) }}" class="p-2 text-primary hover:bg-primary-container/10 rounded-lg transition-colors" title="Ver detalle">
                  <span class="material-symbols-outlined">visibility</span>
                </a>
                <button data-action="edit" data-id="{{ $t->id }}" class="p-2 text-primary hover:bg-primary-container/10 rounded-lg transition-colors" title="Editar">
                  <span class="material-symbols-outlined">edit</span>
                </button>
                <form method="POST" action="{{ route('movimientos.destroy', $t) }}" class="delete-form inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-2 text-error hover:bg-error-container/10 rounded-lg transition-colors" title="Eliminar">
                    <span class="material-symbols-outlined">delete</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">No hay movimientos registrados</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{-- Paginación de la tabla de transacciones --}}
    <div class="bg-surface-container-low px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-outline-variant">
      <p class="text-sm text-on-surface-variant">Mostrando <span class="font-bold">{{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }}</span> de <span class="font-bold">{{ $transactions->total() }}</span> movimientos</p>
      <div class="flex items-center gap-2">
        @if($transactions->onFirstPage())
        <button class="p-2 rounded-lg border border-outline-variant bg-white opacity-50 cursor-not-allowed"><span class="material-symbols-outlined">chevron_left</span></button>
        @else
        <a href="{{ $transactions->previousPageUrl() }}" class="p-2 rounded-lg border border-outline-variant bg-white hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">chevron_left</span></a>
        @endif
        @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="px-3 py-1 rounded-lg {{ $page == $transactions->currentPage() ? 'bg-primary text-white font-bold' : 'hover:bg-surface-container-high' }} text-sm transition-colors">{{ $page }}</a>
        @endforeach
        @if($transactions->hasMorePages())
        <a href="{{ $transactions->nextPageUrl() }}" class="p-2 rounded-lg border border-outline-variant bg-white hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">chevron_right</span></a>
        @else
        <button class="p-2 rounded-lg border border-outline-variant bg-white opacity-50 cursor-not-allowed"><span class="material-symbols-outlined">chevron_right</span></button>
        @endif
      </div>
    </div>
  </div>
  {{-- Resumen de totales: ingresos, egresos y balance neto del mes --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl border border-outline-variant shadow-sm flex flex-col">
      <span class="text-sm text-on-surface-variant mb-1">Total Ingresos (Mes)</span>
      <h4 class="text-2xl font-bold text-tertiary">${{ number_format($monthIncome, 2) }}</h4>
    </div>
    <div class="bg-white p-6 rounded-xl border border-outline-variant shadow-sm flex flex-col">
      <span class="text-sm text-on-surface-variant mb-1">Total Egresos (Mes)</span>
      <h4 class="text-2xl font-bold text-error">${{ number_format($monthExpenses, 2) }}</h4>
    </div>
    <div class="bg-primary text-white p-6 rounded-xl shadow-lg shadow-primary/30 flex flex-col">
      <span class="text-sm opacity-80 mb-1">Balance Neto</span>
      <h4 class="text-2xl font-bold">${{ number_format($netBalance, 2) }}</h4>
      <p class="mt-4 text-xs opacity-70">Actualizado al momento</p>
    </div>
  </div>
</div>

{{-- Modal para crear un nuevo movimiento --}}
<div id="createModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h3 class="font-headline-sm text-headline-sm">Nuevo Movimiento</h3>
      <button id="createCloseBtn" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="POST" action="{{ route('movimientos.store') }}" class="space-y-4">
      @csrf
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Descripción</label>
        <input name="description" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Descripción del movimiento">
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Tipo</label>
          <select name="type" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <option value="credito">Crédito</option>
            <option value="debito">Débito</option>
          </select>
        </div>
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Monto</label>
          <input name="amount" type="number" step="0.01" min="0.01" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="0.00">
        </div>
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Fecha</label>
        <input name="transaction_date" type="date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Categoría</label>
        <input name="category" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Ej: Gastos, Prestamo, Regalo, Sueldo, etc.">
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Referencia</label>
        <input name="reference" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Factura #, ticket, etc.">
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:opacity-90 transition-all">Guardar Movimiento</button>
        <button type="button" id="createCancelBtn" class="flex-1 bg-surface-container-high text-on-surface py-3 rounded-lg font-bold hover:opacity-90 transition-all">Cancelar</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal para editar un movimiento existente --}}
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h3 class="font-headline-sm text-headline-sm">Editar Movimiento</h3>
      <button id="editCloseBtn" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form id="editForm" method="POST" action="" class="space-y-4">
      @csrf
      @method('PUT')
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Descripción</label>
        <input name="description" id="edit_description" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Descripción del movimiento">
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Tipo</label>
          <select name="type" id="edit_type" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <option value="credito">Crédito</option>
            <option value="debito">Débito</option>
          </select>
        </div>
        <div>
          <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Monto</label>
          <input name="amount" id="edit_amount" type="number" step="0.01" min="0.01" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="0.00">
        </div>
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Fecha</label>
        <input name="transaction_date" id="edit_transaction_date" type="date" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Categoría</label>
        <input name="category" id="edit_category" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Ej: Gastos, Prestamo, Regalo, Sueldo, etc.">
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant uppercase block mb-1">Referencia</label>
        <input name="reference" id="edit_reference" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="Factura #, ticket, etc.">
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:opacity-90 transition-all">Actualizar Movimiento</button>
        <button type="button" id="editCancelBtn" class="flex-1 bg-surface-container-high text-on-surface py-3 rounded-lg font-bold hover:opacity-90 transition-all">Cancelar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
function showCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
function hideCreateModal() { document.getElementById('createModal').classList.add('hidden'); }

function showEditModal() { document.getElementById('editModal').classList.remove('hidden'); }
function hideEditModal() { document.getElementById('editModal').classList.add('hidden'); }

function editTransaction(id) {
  fetch('/movimientos/' + id + '/edit')
    .then(function(r) {
      if (!r.ok) { throw new Error('Error ' + r.status); }
      return r.json();
    })
    .then(function(data) {
      document.getElementById('editForm').action = '/movimientos/' + id;
      document.getElementById('edit_description').value = data.description || '';
      document.getElementById('edit_type').value = data.type || 'credito';
      document.getElementById('edit_amount').value = data.amount || '';
      document.getElementById('edit_transaction_date').value = data.transaction_date ? data.transaction_date.substring(0, 10) : '';
      document.getElementById('edit_category').value = data.category || '';
      document.getElementById('edit_reference').value = data.reference || '';
      showEditModal();
    })
    .catch(function(err) {
      alert('Error al cargar movimiento: ' + err.message);
    });
}

document.getElementById('createMovBtn').addEventListener('click', showCreateModal);
document.getElementById('createCloseBtn').addEventListener('click', hideCreateModal);
document.getElementById('createCancelBtn').addEventListener('click', hideCreateModal);
document.getElementById('editCloseBtn').addEventListener('click', hideEditModal);
document.getElementById('editCancelBtn').addEventListener('click', hideEditModal);
document.getElementById('createModal').addEventListener('click', function(e) {
  if (e.target === this) hideCreateModal();
});
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) hideEditModal();
});
document.querySelector('tbody').addEventListener('click', function(e) {
  var btn = e.target.closest('[data-action="edit"]');
  if (btn) editTransaction(btn.dataset.id);
});
document.querySelector('tbody').addEventListener('submit', function(e) {
  if (e.target.classList.contains('delete-form') && !confirm('¿Eliminar este movimiento?')) {
    e.preventDefault();
  }
});
</script>
@endpush
