@extends('layouts.app')

@section('title', 'ContaFlow - Trazabilidad')
@section('page-title', 'Trazabilidad')

@section('content')
  @php
  $actionIcons = [
    'created' => ['icon' => 'add_circle', 'bg' => 'bg-emerald-100', 'color' => 'text-tertiary'],
    'updated' => ['icon' => 'edit_square', 'bg' => 'bg-blue-100', 'color' => 'text-primary'],
    'deleted' => ['icon' => 'delete_forever', 'bg' => 'bg-red-100', 'color' => 'text-error'],
  ];
  $actionLabels = ['created' => 'Creado', 'updated' => 'Editado', 'deleted' => 'Eliminado'];
  @endphp

  <div class="p-6 md:p-8 space-y-6">
    {{-- Buscador y filtros para buscar logs por acción, descripción, ID o fecha --}}
    <div class="bg-white rounded-xl shadow-sm p-card-padding border border-outline-variant flex flex-col lg:flex-row gap-gutter items-center">
      <form method="GET" action="{{ route('trazabilidad') }}" class="w-full flex flex-col lg:flex-row gap-gutter items-center">
        <div class="relative w-full lg:flex-1">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
          <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none text-body-md" placeholder="Buscar por acción, descripción, ID o usuario..." type="text"/>
        </div>
        <div class="flex gap-4 w-full lg:w-auto">
          <input name="date" value="{{ request('date') }}" class="px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none" type="date">
          <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-opacity"><span class="material-symbols-outlined">filter_list</span><span>Filtrar</span></button>
          @if(request('search') || request('date'))
            <a href="{{ route('trazabilidad') }}" class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg font-bold hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined">close</span><span>Limpiar</span></a>
          @endif
        </div>
      </form>
    </div>

    {{-- Lista de logs de auditoría con detalle de cambios (antes/después) para cada entidad --}}
    <div class="flex flex-col gap-4">
      @forelse($logs as $log)
        @php
        $iconData = $actionIcons[$log->action] ?? ['icon' => 'info', 'bg' => 'bg-slate-100', 'color' => 'text-secondary'];
        $title = ucfirst($log->entity_type) . ' ' . ($actionLabels[$log->action] ?? ucfirst($log->action));
        $oldValues = is_string($log->old_values) ? json_decode($log->old_values, true) : $log->old_values;
        $newValues = is_string($log->new_values) ? json_decode($log->new_values, true) : $log->new_values;
        $userName = $log->user->name ?? 'Unknown';
        $userInitialLog = substr($userName, 0, 1);
        @endphp
      <div class="bg-surface-container-low rounded-xl border border-outline-variant hover:shadow-md transition-shadow p-card-padding {{ $log->action === 'deleted' ? 'border-l-4 border-l-error' : '' }}">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
          <div class="flex gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full {{ $iconData['bg'] }} flex items-center justify-center {{ $iconData['color'] }}"><span class="material-symbols-outlined">{{ $iconData['icon'] }}</span></div>
            <div class="flex flex-col">
              <h3 class="font-headline-sm text-headline-sm text-on-background">{{ $title }}</h3>
              <div class="flex items-center gap-2 mt-1">
                <div class="w-6 h-6 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container text-xs font-bold">{{ $userInitialLog }}</div>
                <span class="text-body-md font-bold text-on-surface">{{ $userName }}</span>
                <span class="text-outline">&bull;</span>
                <span class="text-label-md text-outline">{{ $log->created_at->format('M d, Y - h:i A') }}</span>
              </div>
            </div>
          </div>
          <div class="flex items-center">
            @if($log->action === 'deleted')
              <span class="px-3 py-1 bg-error-container text-on-error-container text-label-md rounded-full font-bold">ACCION CRÍTICA</span>
            @else
              @php $entityRoute = $log->entity_type === 'transaction' ? route('movimientos.show', $log->entity_id) : '#'; @endphp
              <a class="flex items-center gap-2 text-primary font-bold hover:underline" href="{{ $entityRoute }}">
                <span>Ver {{ $log->entity_type }} #{{ $log->entity_id }}</span>
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
              </a>
            @endif
          </div>
        </div>
        @if($log->action === 'deleted')
          <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white rounded-lg border border-outline-variant"><span class="text-label-md text-outline uppercase block mb-1">Motivo</span><p class="text-body-md text-on-surface">{{ $log->description }}</p></div>
            <div class="p-4 bg-white rounded-lg border border-outline-variant"><span class="text-label-md text-outline uppercase block mb-1">ID Eliminado</span><p class="text-body-md text-on-surface font-mono">{{ $log->entity_id }}</p></div>
          </div>
        @elseif($oldValues && $newValues)
          <div class="mt-6 bg-white rounded-lg p-4 border border-outline-variant overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead><tr class="border-b border-outline-variant bg-surface-container-lowest"><th class="py-2 px-3 font-label-md text-label-md text-outline uppercase tracking-wider">Campo</th><th class="py-2 px-3 font-label-md text-label-md text-outline uppercase tracking-wider">Antes</th><th class="py-2 px-3 font-label-md text-label-md text-outline uppercase tracking-wider">Después</th></tr></thead>
              <tbody class="text-body-md">
                @foreach($oldValues as $key => $value)
                  <tr class="border-b border-outline-variant">
                    <td class="py-3 px-3 font-bold capitalize">{{ str_replace('_', ' ', $key) }}</td>
                    <td class="py-3 px-3 text-error line-through">{{ $value }}</td>
                    <td class="py-3 px-3 text-tertiary font-bold">{{ $newValues[$key] ?? '' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="mt-4 text-body-md text-on-surface-variant">{{ $log->description }}</p>
        @endif
      </div>
      @empty
        <p class="text-center text-on-surface-variant py-8">No hay registros de trazabilidad</p>
      @endforelse
    </div>

    <div class="flex items-center justify-between">
      <span class="text-label-md text-outline">Mostrando {{ $logs->firstItem() }}-{{ $logs->lastItem() }} de {{ $logs->total() }} registros</span>
      <div class="flex gap-2">
        @if($logs->onFirstPage())
          <button class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded-lg opacity-50 cursor-not-allowed"><span class="material-symbols-outlined">chevron_left</span></button>
        @else
          <a href="{{ $logs->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">chevron_left</span></a>
        @endif
        @for($i = 1; $i <= $logs->lastPage(); $i++)
          <a href="{{ $logs->url($i) }}" class="w-10 h-10 flex items-center justify-center rounded-lg font-bold transition-colors {{ $logs->currentPage() === $i ? 'bg-primary text-on-primary' : 'border border-outline-variant hover:bg-surface-container-low' }}">{{ $i }}</a>
        @endfor
        @if($logs->hasMorePages())
          <a href="{{ $logs->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">chevron_right</span></a>
        @else
          <button class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded-lg opacity-50 cursor-not-allowed"><span class="material-symbols-outlined">chevron_right</span></button>
        @endif
      </div>
    </div>
  </div>
@endsection
