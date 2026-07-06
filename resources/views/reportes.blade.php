@extends('layouts.app')

@section('title', 'ContaFlow - Reportes')
@section('page-title')
<span class="material-symbols-outlined">assessment</span> Análisis Financiero
@endsection

@section('content')
  @php
  $total = $totalIncome + $totalExpenses;
  $incomePct = $total > 0 ? round($totalIncome / $total * 100) : 0;
  $expensePct = $total > 0 ? round($totalExpenses / $total * 100) : 0;
  $totalRp = $receivables + $payables;
  $receivablesPct = $totalRp > 0 ? round($receivables / $totalRp * 100) : 0;
  $payablesPct = $totalRp > 0 ? round($payables / $totalRp * 100) : 0;

  @endphp
  <div class="p-6 md:p-8 space-y-6 max-w-[1200px] mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
      <div>
        <p class="text-label-md text-on-surface-variant">Resumen financiero basado en tus movimientos registrados.</p>
      </div>
      <a href="{{ route('reportes.export') }}"
         class="flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-all text-sm shadow-sm">
        <span class="material-symbols-outlined text-[18px]">file_download</span>
        Exportar Excel
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
      {{-- Resumen ejecutivo con ingresos totales, egresos totales, barra de proporción y utilidad neta --}}
      <div class="md:col-span-12 lg:col-span-12 bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
        <div class="flex justify-between items-center mb-6">
          <h2 class="font-headline-sm text-headline-sm text-on-surface">Resumen Ejecutivo</h2>
          <span class="material-symbols-outlined text-outline">info</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter mb-8">
          <div class="p-4 bg-tertiary/5 rounded-xl border border-tertiary/10">
            <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider mb-2">Ingresos Totales</p>
            <p class="text-display-lg font-display-lg text-tertiary">${{ number_format($totalIncome, 2) }}</p>
          </div>
          <div class="p-4 bg-error/5 rounded-xl border border-error/10">
            <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider mb-2">Egresos Totales</p>
            <p class="text-display-lg font-display-lg text-error">${{ number_format($totalExpenses, 2) }}</p>
          </div>
        </div>
        <div class="relative w-full h-10 bg-surface-container-high rounded-lg overflow-hidden flex">
          <div class="h-full bg-tertiary flex items-center justify-center px-4" style="width: {{ $incomePct }}%;"><span class="text-white text-[10px] font-bold whitespace-nowrap">Ingresos ({{ $incomePct }}%)</span></div>
          <div class="h-full bg-error flex items-center justify-center px-4" style="width: {{ $expensePct }}%;"><span class="text-white text-[10px] font-bold whitespace-nowrap">Egresos ({{ $expensePct }}%)</span></div>
        </div>
        <div class="mt-6 p-4 bg-surface-container-low rounded-xl flex justify-between items-center">
          <span class="text-body-md font-medium text-on-surface-variant">Utilidad Neta Real</span>
          <span class="text-headline-sm font-bold text-primary">${{ number_format($netProfit, 2) }}</span>
        </div>
      </div>

      {{-- Gráfico de tendencia de flujo de caja mensual con tooltip interactivo --}}
      <div class="md:col-span-12 bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
          <div><h2 class="font-headline-sm text-headline-sm text-on-surface">Tendencia de Flujo de Caja</h2><p class="text-body-md text-on-surface-variant">Progreso mensual del año actual</p></div>
          <div class="flex items-center gap-4"><div class="flex items-center gap-2"><span class="w-3 h-0.5 bg-primary"></span><span class="text-label-md font-label-md">Ingresos</span></div></div>
        </div>
        @if($monthlyData->isEmpty())
          <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-[48px] mb-2">show_chart</span>
            <p class="text-body-md">Sin datos mensuales para mostrar</p>
          </div>
        @else
        @php
          $allValues = $monthlyData->map(fn($r) => (float)$r->income);
          $valCount = $allValues->count();
          $valMin = 0;
          $valMax = max(1, $allValues->max());
          $valRange = $valMax - $valMin;
          $cW = 800;
          $cH = 220;
          $pad = 55;
          $plotH = $cH - $pad * 2;
          $plotW = $cW - $pad * 2;

          $coords = $allValues->map(fn($v, $i) => [
            'x'       => round($pad + ($i / max($valCount - 1, 1)) * $plotW),
            'y'       => round($pad + $plotH - (($v - $valMin) / $valRange) * $plotH),
            'label'   => $monthlyData[$i]->month,
            'income'  => number_format((float)$monthlyData[$i]->income, 2),
            'expenses'=> number_format((float)$monthlyData[$i]->expenses, 2),
            'net'     => number_format((float)$monthlyData[$i]->income - (float)$monthlyData[$i]->expenses, 2),
          ]);

          $segments = [];
          $first = true;
          foreach ($coords as $c) {
            $segments[] = $first ? "M {$c['x']} {$c['y']}" : "L {$c['x']} {$c['y']}";
            $first = false;
          }
          $pathD = implode(' ', $segments);
          $lastC = $coords->last();
          $areaD = $pathD . " L {$lastC['x']} {$cH} L {$pad} {$cH} Z";

          $yLabels = [];
          for ($i = 0; $i <= 4; $i++) {
            $val = $valMin + ($valRange * $i / 4);
            $yLabels[] = [
              'y' => round($pad + $plotH - ($i / 4) * $plotH),
              'label' => '$' . number_format($val, 0),
            ];
          }
        @endphp
        <div class="w-full relative" id="repChartContainer">
          <svg class="w-full h-64 overflow-visible" viewBox="0 0 {{ $cW }} {{ $cH }}" id="repChart">
            <defs>
              <linearGradient id="repGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#004ac6" stop-opacity="0.2"/>
                <stop offset="100%" stop-color="#004ac6" stop-opacity="0"/>
              </linearGradient>
            </defs>
            @foreach($yLabels as $l)
              <text x="{{ $pad - 8 }}" y="{{ $l['y'] + 4 }}" text-anchor="end" fill="#737686" font-size="10" font-family="Inter,sans-serif">{{ $l['label'] }}</text>
              <line stroke="#E2E8F0" stroke-dasharray="3,3" x1="{{ $pad }}" x2="{{ $cW }}" y1="{{ $l['y'] }}" y2="{{ $l['y'] }}"/>
            @endforeach
            <line stroke="#CBD5E1" x1="{{ $pad }}" x2="{{ $cW }}" y1="{{ $cH - $pad }}" y2="{{ $cH - $pad }}"/>
            <path d="{{ $areaD }}" fill="url(#repGradient)"/>
            <path d="{{ $pathD }}" fill="none" stroke="#004ac6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
            @foreach($coords as $i => $c)
              <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" fill="#004ac6" r="4" stroke="white" stroke-width="2"
                      data-index="{{ $i }}" data-label="{{ $c['label'] }}" data-income="{{ $c['income'] }}"
                      data-expenses="{{ $c['expenses'] }}" data-net="{{ $c['net'] }}"
                      class="rep-point cursor-pointer"
                      onmouseenter="highlightRepPoint({{ $i }}, true)" onmouseleave="highlightRepPoint({{ $i }}, false)"/>
            @endforeach
            <rect x="{{ $pad }}" y="0" width="{{ $plotW }}" height="{{ $cH }}" fill="transparent"
                  onmousemove="trackRepMove(event)" onmouseleave="hideRepTooltip()"/>
          </svg>
          <div class="flex justify-between mt-2 px-2 text-[10px] font-bold text-on-surface-variant uppercase">
            @foreach($monthlyData as $p)
              <span>{{ $p->month }}</span>
            @endforeach
          </div>
          <div id="repTooltip" class="hidden absolute pointer-events-none z-10 bg-inverse-surface text-inverse-on-surface px-3 py-2 rounded-lg shadow-xl text-xs whitespace-nowrap">
            <div id="repTtLabel" class="font-semibold mb-1"></div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
              <span>Ingresos:</span><span id="repTtIncome" class="text-right font-medium text-tertiary"></span>
              <span>Egresos:</span><span id="repTtExpenses" class="text-right font-medium text-error"></span>
              <span class="font-semibold">Neto:</span><span id="repTtNet" class="text-right font-bold"></span>
            </div>
          </div>
        </div>
        @push('scripts')
        <script>
        var repData = @json($coords);
        function highlightRepPoint(idx, show) {
          var pts = document.querySelectorAll('.rep-point');
          pts.forEach(function(p) { p.setAttribute('r', '4'); p.setAttribute('stroke-width', '2'); });
          if (!show) { hideRepTooltip(); return; }
          var pt = pts[idx]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          positionRepTooltip(pt, idx);
        }
        function trackRepMove(e) {
          var svg = document.getElementById('repChart');
          var rect = svg.getBoundingClientRect();
          var svgX = e.clientX - rect.left;
          var svgW = rect.width;
          var viewW = {{ $cW }};
          var chartX = (svgX / svgW) * viewW;
          var closest = 0, minDist = Infinity;
          repData.forEach(function(p, i) {
            var d = Math.abs(p.x - chartX);
            if (d < minDist) { minDist = d; closest = i; }
          });
          var pts = document.querySelectorAll('.rep-point');
          pts.forEach(function(p) { p.setAttribute('r', '4'); p.setAttribute('stroke-width', '2'); });
          var pt = pts[closest]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          positionRepTooltip(pt, closest);
        }
        function positionRepTooltip(pt, idx) {
          var tooltip = document.getElementById('repTooltip');
          var container = document.getElementById('repChartContainer');
          var svg = document.getElementById('repChart');
          var svgRect = svg.getBoundingClientRect();
          var contRect = container.getBoundingClientRect();
          var scaleX = svgRect.width / {{ $cW }};
          var scaleY = svgRect.height / {{ $cH }};
          var cx = parseFloat(pt.getAttribute('cx')) * scaleX;
          var cy = parseFloat(pt.getAttribute('cy')) * scaleY;
          document.getElementById('repTtLabel').textContent = repData[idx].label;
          document.getElementById('repTtIncome').textContent = '$' + repData[idx].income;
          document.getElementById('repTtExpenses').textContent = '$' + repData[idx].expenses;
          document.getElementById('repTtNet').textContent = '$' + repData[idx].net;
          var left = cx - tooltip.offsetWidth / 2;
          var top = cy - tooltip.offsetHeight - 10;
          if (top < 0) top = cy + 10;
          if (left < 0) left = 4;
          if (left + tooltip.offsetWidth > contRect.width - 4) left = contRect.width - tooltip.offsetWidth - 4;
          tooltip.style.left = Math.round(left) + 'px';
          tooltip.style.top = Math.round(top) + 'px';
          tooltip.classList.remove('hidden');
        }
        function hideRepTooltip() {
          document.getElementById('repTooltip').classList.add('hidden');
        }
        </script>
        @endpush
        @endif
      </div>

      {{-- Cuentas por cobrar y por pagar con barras de progreso --}}
      <div class="md:col-span-12 lg:col-span-6 bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Cuentas por Cobrar vs Pagar</h2>
        <div class="space-y-6">
          <div class="p-4 bg-surface-container-low rounded-xl">
            <div class="flex justify-between items-end mb-2">
              <div>
                <p class="text-label-md font-label-md text-on-surface-variant uppercase">Por Cobrar</p>
                <p class="text-headline-sm font-bold text-tertiary">${{ number_format($receivables, 2) }}</p>
              </div>
              <span class="text-body-md font-medium text-on-surface-variant">{{ $receivablesPct }}% Cobrado</span>
            </div>
            <div class="w-full h-3 bg-surface-container-high rounded-full overflow-hidden">
              <div class="h-full bg-tertiary" style="width: {{ $receivablesPct }}%;"></div>
            </div>
          </div>
          <div class="p-4 bg-surface-container-low rounded-xl">
            <div class="flex justify-between items-end mb-2">
              <div>
                <p class="text-label-md font-label-md text-on-surface-variant uppercase">Por Pagar</p>
                <p class="text-headline-sm font-bold text-error">${{ number_format($payables, 2) }}</p>
              </div>
              <span class="text-body-md font-medium text-on-surface-variant">{{ $payablesPct }}% Pagado</span>
            </div>
            <div class="w-full h-3 bg-surface-container-high rounded-full overflow-hidden">
              <div class="h-full bg-error" style="width: {{ $payablesPct }}%;"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Gastos agrupados por categoría con barras horizontales --}}
      <div class="md:col-span-12 lg:col-span-6 bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Gastos por Categoría</h2>
        <div class="space-y-5">
          @forelse($expensesByCategory as $cat)
            <div class="space-y-2">
              <div class="flex justify-between items-center text-body-md font-body-md">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-{{ $loop->index === 0 ? 'primary' : ($loop->index === 1 ? 'secondary' : ($loop->index === 2 ? 'tertiary-container' : 'outline')) }}"></span>
                  <span>{{ $cat->category }}</span>
                </div>
                <span class="font-numeric-md font-bold text-on-surface">${{ number_format($cat->total, 2) }}</span>
              </div>
              <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="h-full bg-{{ $loop->index === 0 ? 'primary' : ($loop->index === 1 ? 'secondary' : ($loop->index === 2 ? 'tertiary-container' : 'outline')) }}" style="width: {{ $totalExpenses > 0 ? ($cat->total / $totalExpenses * 100) : 0 }}%;"></div>
              </div>
            </div>
          @empty
            <p class="text-on-surface-variant text-center">Sin datos de gastos</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
