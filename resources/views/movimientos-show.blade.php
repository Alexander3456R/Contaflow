@extends('layouts.app')

@section('title', 'ContaFlow - Movimiento #' . $movimiento->id)
@section('page-title')
<span class="material-symbols-outlined">receipt</span> Detalle del Movimiento
@endsection

@section('content')
<div class="p-6 md:p-8 space-y-6">
  <a href="{{ route('movimientos') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline mb-4">
    <span class="material-symbols-outlined">arrow_back</span>
    Volver a Movimientos
  </a>

  {{-- Tarjeta de detalle de transacción con descripción, monto, fecha, saldo, categoría y referencia --}}
  <div class="bg-white rounded-xl shadow-md border border-outline-variant overflow-hidden">
    <div class="px-6 py-5 border-b border-outline-variant bg-surface-container-low flex items-center justify-between">
      <div>
        <h3 class="font-headline-sm text-headline-sm text-on-surface">Movimiento #{{ $movimiento->id }}</h3>
        <p class="text-on-surface-variant text-body-md">Registrado el {{ $movimiento->created_at->format('d/m/Y - h:i A') }}</p>
      </div>
      <span class="px-4 py-2 rounded-full text-sm font-bold {{ $movimiento->type === 'credito' ? 'bg-tertiary-fixed text-on-tertiary-fixed-variant' : 'bg-error-container text-on-error-container' }}">
        {{ $movimiento->type === 'credito' ? 'Crédito' : 'Débito' }}
      </span>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Descripción</span>
        <p class="text-body-lg font-bold text-on-surface">{{ $movimiento->description }}</p>
      </div>
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Monto</span>
        <p class="text-body-lg font-bold {{ $movimiento->type === 'credito' ? 'text-tertiary' : 'text-error' }}">
          {{ $movimiento->type === 'credito' ? '+' : '-' }}${{ number_format((float)$movimiento->amount, 2) }}
        </p>
      </div>
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Fecha de la Transacción</span>
        <p class="text-body-md text-on-surface">{{ $movimiento->transaction_date->format('d/m/Y') }}</p>
      </div>
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Saldo después del Movimiento</span>
        <p class="text-body-md font-bold text-on-surface">${{ number_format((float)$movimiento->balance, 2) }}</p>
      </div>
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Categoría</span>
        <p class="text-body-md text-on-surface">{{ $movimiento->category ?? 'Sin categoría' }}</p>
      </div>
      <div>
        <span class="text-label-md text-outline uppercase block mb-1">Referencia</span>
        <p class="text-body-md text-on-surface">{{ $movimiento->reference ?? 'Sin referencia' }}</p>
      </div>
    </div>

    <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low flex justify-end">
      <a href="{{ route('movimientos') }}" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-opacity">Volver al listado</a>
    </div>
  </div>
</div>
@endsection
