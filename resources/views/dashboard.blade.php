@extends('layouts.app')

@section('title', 'ContaFlow - Dashboard')
@section('page-title')
<span class="material-symbols-outlined">dashboard</span> Dashboard
@endsection

@section('content')
  <div class="p-6 md:p-8 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
      {{-- Tarjeta de saldo actual --}}
      <div class="bg-white p-card-padding rounded-xl shadow-md border border-outline-variant flex flex-col justify-between h-32 hover:shadow-lg transition-shadow">
        <div class="flex justify-between items-start">
          <span class="font-label-md text-on-surface-variant uppercase tracking-wider">Saldo Actual</span>
          <div class="p-2 bg-primary-container/10 rounded-lg text-primary">
            <span class="material-symbols-outlined">account_balance_wallet</span>
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="font-display-lg text-display-lg text-tertiary-container">${{ number_format($currentBalance, 2) }}</span>
        </div>
      </div>
      {{-- Tarjeta de ingresos del mes --}}
      <div class="bg-white p-card-padding rounded-xl shadow-md border border-outline-variant flex flex-col justify-between h-32 hover:shadow-lg transition-shadow">
        <div class="flex justify-between items-start">
          <span class="font-label-md text-on-surface-variant uppercase tracking-wider">Ingresos (Mes)</span>
          <div class="p-2 bg-tertiary/10 rounded-lg text-tertiary">
            <span class="material-symbols-outlined">trending_up</span>
          </div>
        </div>
        <div>
          <span class="font-headline-md text-headline-md text-tertiary">${{ number_format($monthIncome, 2) }}</span>
        </div>
      </div>
      {{-- Tarjeta de egresos del mes --}}
      <div class="bg-white p-card-padding rounded-xl shadow-md border border-outline-variant flex flex-col justify-between h-32 hover:shadow-lg transition-shadow">
        <div class="flex justify-between items-start">
          <span class="font-label-md text-on-surface-variant uppercase tracking-wider">Egresos (Mes)</span>
          <div class="p-2 bg-error-container text-error rounded-lg">
            <span class="material-symbols-outlined">trending_down</span>
          </div>
        </div>
        <div>
          <span class="font-headline-md text-headline-md text-error">${{ number_format($monthExpenses, 2) }}</span>
        </div>
      </div>
    </div>

    {{-- Comparativa Mes Actual vs Mes Anterior --}}
    @php
      $prevName = now()->subMonth()->isoFormat('MMMM');
      $currName = now()->isoFormat('MMMM');
      $prevNet = (float)$prevMonthIncome - (float)$prevMonthExpenses;
      $currNet = (float)$monthIncome - (float)$monthExpenses;

      $incChange = $prevMonthIncome > 0 ? round(((float)$monthIncome - (float)$prevMonthIncome) / (float)$prevMonthIncome * 100) : 0;
      $expChange = $prevMonthExpenses > 0 ? round(((float)$monthExpenses - (float)$prevMonthExpenses) / (float)$prevMonthExpenses * 100) : 0;
      $netChange = $prevNet != 0 ? round(($currNet - $prevNet) / abs($prevNet) * 100) : 0;
    @endphp
    <div class="bg-white p-card-padding rounded-xl shadow-md border border-outline-variant">
      <h3 class="font-headline-sm text-headline-sm text-on-surface mb-6">Comparativa Mensual</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Mes anterior --}}
        @php
          $pIncChange = $prevPrevIncome > 0 ? round(((float)$prevMonthIncome - (float)$prevPrevIncome) / (float)$prevPrevIncome * 100) : 0;
          $pExpChange = $prevPrevExpenses > 0 ? round(((float)$prevMonthExpenses - (float)$prevPrevExpenses) / (float)$prevPrevExpenses * 100) : 0;
          $pPrevNet = (float)$prevPrevIncome - (float)$prevPrevExpenses;
          $pNetChange = $pPrevNet != 0 ? round(($prevNet - $pPrevNet) / abs($pPrevNet) * 100) : 0;
        @endphp
        <div class="p-5 bg-surface-container-low rounded-xl border border-outline-variant/30">
          <p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-4">{{ ucfirst($prevName) }}</p>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md text-on-surface">Ingresos</span>
                <span class="flex items-center text-xs font-bold {{ $pIncChange >= 0 ? 'text-tertiary' : 'text-error' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $pIncChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($pIncChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold text-tertiary">${{ number_format((float)$prevMonthIncome, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md text-on-surface">Egresos</span>
                <span class="flex items-center text-xs font-bold {{ $pExpChange >= 0 ? 'text-error' : 'text-tertiary' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $pExpChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($pExpChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold text-error">${{ number_format((float)$prevMonthExpenses, 2) }}</span>
            </div>
            <div class="pt-3 border-t border-outline-variant/50 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md font-semibold text-on-surface">Neto</span>
                <span class="flex items-center text-xs font-bold {{ $pNetChange >= 0 ? 'text-tertiary' : 'text-error' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $pNetChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($pNetChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold {{ $prevNet >= 0 ? 'text-tertiary' : 'text-error' }}">${{ number_format($prevNet, 2) }}</span>
            </div>
          </div>
        </div>

        {{-- Mes actual --}}
        <div class="p-5 rounded-xl border border-primary/20 bg-primary/5">
          <p class="font-label-md text-primary uppercase tracking-wider mb-4">{{ ucfirst($currName) }}</p>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md text-on-surface">Ingresos</span>
                <span class="flex items-center text-xs font-bold {{ $incChange >= 0 ? 'text-tertiary' : 'text-error' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $incChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($incChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold text-tertiary">${{ number_format((float)$monthIncome, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md text-on-surface">Egresos</span>
                <span class="flex items-center text-xs font-bold {{ $expChange >= 0 ? 'text-error' : 'text-tertiary' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $expChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($expChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold text-error">${{ number_format((float)$monthExpenses, 2) }}</span>
            </div>
            <div class="pt-3 border-t border-primary/20 flex justify-between items-center">
              <div class="flex items-center gap-2">
                <span class="text-body-md font-semibold text-on-surface">Neto</span>
                <span class="flex items-center text-xs font-bold {{ $netChange >= 0 ? 'text-tertiary' : 'text-error' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $netChange >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                  {{ abs($netChange) }}%
                </span>
              </div>
              <span class="text-numeric-md font-bold {{ $currNet >= 0 ? 'text-tertiary' : 'text-error' }}">${{ number_format($currNet, 2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Timeline visual de movimientos recientes --}}
    <div class="bg-white rounded-xl shadow-md border border-outline-variant">
      <div class="p-card-padding border-b border-outline-variant flex justify-between items-center">
        <h3 class="font-headline-sm text-headline-sm text-on-surface">Movimientos Recientes</h3>
        <a href="{{ route('movimientos') }}" class="text-primary font-bold hover:underline font-label-md">Ver todos</a>
      </div>
      <div class="px-6 py-4">
        @forelse($recentTransactions as $t)
          <div class="relative flex gap-4 pb-6 last:pb-0">
            {{-- Línea vertical --}}
            <div class="flex flex-col items-center">
              <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $t->type === 'credito' ? 'bg-tertiary/10 text-tertiary' : 'bg-error/10 text-error' }}">
                <span class="material-symbols-outlined text-[20px]">{{ $t->type === 'credito' ? 'arrow_downward' : 'arrow_upward' }}</span>
              </div>
              @if(!$loop->last)
                <div class="w-px flex-1 bg-outline-variant/50 mt-1"></div>
              @endif
            </div>
            {{-- Contenido --}}
            <div class="flex-1 min-w-0 pt-1">
              <div class="flex justify-between items-start gap-2">
                <div class="min-w-0">
                  <p class="font-body-md text-body-md text-on-surface truncate">{{ $t->description }}</p>
                  <p class="font-label-md text-label-md text-on-surface-variant mt-0.5">{{ $t->transaction_date->isoFormat('D [de] MMM [del] YYYY') }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="font-numeric-md text-numeric-md font-bold {{ $t->type === 'credito' ? 'text-tertiary' : 'text-error' }}">
                    {{ $t->type === 'credito' ? '+' : '-' }}${{ number_format($t->amount, 2) }}
                  </p>
                  <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $t->type === 'credito' ? 'bg-tertiary/10 text-tertiary' : 'bg-error/10 text-error' }}">
                    {{ $t->type === 'credito' ? 'Credito' : 'Debito' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="flex flex-col items-center justify-center py-8 text-on-surface-variant">
            <span class="material-symbols-outlined text-[48px] mb-2">receipt_long</span>
            <p class="text-body-md">No hay movimientos recientes</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
