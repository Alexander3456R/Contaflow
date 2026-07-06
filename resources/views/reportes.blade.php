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
  @endphp
  <div class="p-6 md:p-8 space-y-6">
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

      {{-- Gráfico de Ingresos vs Egresos con filtro de fechas --}}
      <div class="md:col-span-12 bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <h3 class="font-headline-sm text-headline-sm text-on-surface">Ingresos vs Egresos</h3>
          <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('reportes', ['range' => '7d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '7d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">7D</a>
            <a href="{{ route('reportes', ['range' => '30d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '30d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">30D</a>
            <a href="{{ route('reportes', ['range' => '90d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '90d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">90D</a>
            <a href="{{ route('reportes', ['range' => '1y']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '1y' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">1A</a>
            <a href="{{ route('reportes', ['range' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === 'all' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">Todo</a>
            <button onclick="document.getElementById('repCustomRange').classList.toggle('hidden')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === 'custom' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">
              <span class="material-symbols-outlined text-[14px]">calendar_month</span>
            </button>
          </div>
        </div>
        <form id="repCustomRange" method="GET" action="{{ route('reportes') }}" class="{{ $range === 'custom' ? '' : 'hidden' }} flex items-center gap-3 mb-4 px-1">
          <input type="hidden" name="range" value="custom">
          <label class="text-xs text-on-surface-variant">Desde:</label>
          <input type="date" name="from" value="{{ $customFrom ?? '' }}" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          <label class="text-xs text-on-surface-variant">Hasta:</label>
          <input type="date" name="to" value="{{ $customTo ?? '' }}" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          <button type="submit" class="px-4 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:opacity-90">Aplicar</button>
        </form>
        @if($chartData->isNotEmpty())
          <div class="text-label-md text-on-surface-variant mb-2">{{ $rangeLabel }}</div>
        @endif
        @if($chartData->isEmpty())
          <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-[48px] mb-2">show_chart</span>
            <p class="text-body-md">Sin datos para mostrar</p>
          </div>
        @else
        @php
          $allIncome = $chartData->pluck('income');
          $allExpenses = $chartData->pluck('expenses');
          $allVals = $allIncome->merge($allExpenses);
          $count = max($chartData->count(), 2);
          $cMin = 0;
          $cMax = max(1, $allVals->max());
          $cRange = $cMax - $cMin;
          $cW = 800;
          $cH = 220;
          $pad = 55;
          $plotH = $cH - $pad * 2;
          $plotW = $cW - $pad * 2;

          $coords = $allIncome->map(fn($v, $i) => [
            'x'        => round($pad + ($i / max($count - 1, 1)) * $plotW),
            'yIncome'  => round($pad + $plotH - ($v / $cRange) * $plotH),
            'yExpense' => round($pad + $plotH - ($allExpenses[$i] / $cRange) * $plotH),
            'date'     => $chartData[$i]->date->format('M d, Y'),
            'income'   => number_format((float)$chartData[$i]->income, 2),
            'expenses' => number_format((float)$chartData[$i]->expenses, 2),
            'net'      => number_format((float)$chartData[$i]->net, 2),
          ]);

          $incSegs = []; $expSegs = []; $first = true;
          foreach ($coords as $c) {
            $incSegs[] = $first ? "M {$c['x']} {$c['yIncome']}" : "L {$c['x']} {$c['yIncome']}";
            $expSegs[] = $first ? "M {$c['x']} {$c['yExpense']}" : "L {$c['x']} {$c['yExpense']}";
            $first = false;
          }
          $incPath = implode(' ', $incSegs);
          $expPath = implode(' ', $expSegs);
          $lastC = $coords->last();
          $incArea = $incPath . " L {$lastC['x']} {$cH} L {$pad} {$cH} Z";
          $expArea = $expPath . " L {$lastC['x']} {$cH} L {$pad} {$cH} Z";

          $yLabels = [];
          for ($i = 0; $i <= 4; $i++) {
            $val = $cMin + ($cRange * $i / 4);
            $yLabels[] = [
              'y'     => round($pad + $plotH - ($i / 4) * $plotH),
              'label' => '$' . number_format($val, 0),
            ];
          }
        @endphp
        <div class="relative flex-grow" id="repChartContainer">
          <div class="flex items-center gap-4 mb-3">
            <div class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-primary"></span><span class="text-label-md font-label-md">Ingresos</span></div>
            <div class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-error"></span><span class="text-label-md font-label-md">Egresos</span></div>
          </div>
          <svg class="w-full h-full overflow-visible" viewBox="0 0 {{ $cW }} {{ $cH }}" id="repChart">
            <defs>
              <linearGradient id="repGradInc" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#004ac6" stop-opacity="0.15"/>
                <stop offset="100%" stop-color="#004ac6" stop-opacity="0"/>
              </linearGradient>
              <linearGradient id="repGradExp" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#ba1a1a" stop-opacity="0.12"/>
                <stop offset="100%" stop-color="#ba1a1a" stop-opacity="0"/>
              </linearGradient>
            </defs>
            @foreach($yLabels as $l)
              <text x="{{ $pad - 8 }}" y="{{ $l['y'] + 4 }}" text-anchor="end" fill="#737686" font-size="10" font-family="Inter,sans-serif">{{ $l['label'] }}</text>
              <line stroke="#E2E8F0" stroke-dasharray="3,3" x1="{{ $pad }}" x2="{{ $cW }}" y1="{{ $l['y'] }}" y2="{{ $l['y'] }}"/>
            @endforeach
            <line stroke="#CBD5E1" x1="{{ $pad }}" x2="{{ $cW }}" y1="{{ $cH - $pad }}" y2="{{ $cH - $pad }}"/>
            <path d="{{ $incArea }}" fill="url(#repGradInc)"/>
            <path d="{{ $incPath }}" fill="none" stroke="#004ac6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
            <path d="{{ $expPath }}" fill="none" stroke="#ba1a1a" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
            @foreach($coords as $i => $c)
              <circle cx="{{ $c['x'] }}" cy="{{ $c['yIncome'] }}" fill="#004ac6" r="4" stroke="white" stroke-width="2"
                      data-index="{{ $i }}" data-date="{{ $c['date'] }}" data-income="{{ $c['income'] }}"
                      data-expenses="{{ $c['expenses'] }}" data-net="{{ $c['net'] }}"
                      class="rep-point cursor-pointer"
                      onmouseenter="highlightRepPoint({{ $i }}, true)" onmouseleave="highlightRepPoint({{ $i }}, false)"/>
              <circle cx="{{ $c['x'] }}" cy="{{ $c['yExpense'] }}" fill="#ba1a1a" r="4" stroke="white" stroke-width="2"
                      data-index="{{ $i }}" data-date="{{ $c['date'] }}" data-income="{{ $c['income'] }}"
                      data-expenses="{{ $c['expenses'] }}" data-net="{{ $c['net'] }}"
                      class="rep-point cursor-pointer"
                      onmouseenter="highlightRepPoint({{ $i }}, true)" onmouseleave="highlightRepPoint({{ $i }}, false)"/>
            @endforeach
            <rect x="{{ $pad }}" y="0" width="{{ $plotW }}" height="{{ $cH }}" fill="transparent"
                  onmousemove="trackRepMove(event)" onmouseleave="hideRepTooltip()"/>
          </svg>
          <div id="repTooltip" class="hidden absolute pointer-events-none z-10 bg-inverse-surface text-inverse-on-surface px-3 py-2 rounded-lg shadow-xl text-xs whitespace-nowrap">
            <div id="repTtDate" class="font-semibold mb-1"></div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
              <span>Ingresos:</span><span id="repTtIncome" class="text-right font-medium text-tertiary"></span>
              <span>Egresos:</span><span id="repTtExpenses" class="text-right font-medium text-error"></span>
              <span class="font-semibold">Neto:</span><span id="repTtNet" class="text-right font-bold"></span>
            </div>
          </div>
        </div>
        @push('scripts')
        <script nonce="{{ $cspNonce }}">
        var repData = @json($coords);
        function highlightRepPoint(idx, show) {
          var pts = document.querySelectorAll('.rep-point');
          pts.forEach(function(p) { p.setAttribute('r', '4'); p.setAttribute('stroke-width', '2'); });
          if (!show) { hideRepTooltip(); return; }
          var pt = pts[idx * 2]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          var pt2 = pts[idx * 2 + 1];
          if (pt2) { pt2.setAttribute('r', '7'); pt2.setAttribute('stroke-width', '3'); }
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
          var pt = pts[closest * 2]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          var pt2 = pts[closest * 2 + 1];
          if (pt2) { pt2.setAttribute('r', '7'); pt2.setAttribute('stroke-width', '3'); }
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
          document.getElementById('repTtDate').textContent = repData[idx].date;
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

      {{-- Gastos e Ingresos por categoría en grid de 2 columnas --}}
      <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-gutter">
        <div class="bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
          <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Gastos por Categor&iacute;a</h2>
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

        <div class="bg-surface-container-lowest rounded-xl p-card-padding shadow-sm border border-slate-200">
          <h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Ingresos por Categor&iacute;a</h2>
          <div class="space-y-5">
            @forelse($incomeByCategory as $cat)
              <div class="space-y-2">
                <div class="flex justify-between items-center text-body-md font-body-md">
                  <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-{{ $loop->index === 0 ? 'tertiary' : ($loop->index === 1 ? 'secondary-container' : ($loop->index === 2 ? 'primary-container' : 'outline')) }}"></span>
                    <span>{{ $cat->category }}</span>
                  </div>
                  <span class="font-numeric-md font-bold text-on-surface">${{ number_format($cat->total, 2) }}</span>
                </div>
                <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
                  <div class="h-full bg-{{ $loop->index === 0 ? 'tertiary' : ($loop->index === 1 ? 'secondary-container' : ($loop->index === 2 ? 'primary-container' : 'outline')) }}" style="width: {{ $totalIncome > 0 ? ($cat->total / $totalIncome * 100) : 0 }}%;"></div>
                </div>
              </div>
            @empty
              <p class="text-on-surface-variant text-center">Sin datos de ingresos</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
