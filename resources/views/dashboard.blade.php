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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
      {{-- Gráfico de evolución del balance con selector de rango de fechas --}}
      <div class="lg:col-span-2 bg-white p-card-padding rounded-xl shadow-md border border-outline-variant flex flex-col">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <h3 class="font-headline-sm text-headline-sm text-on-surface">Evoluci&oacute;n de Balance</h3>
          <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('dashboard', ['range' => '7d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '7d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">7D</a>
            <a href="{{ route('dashboard', ['range' => '30d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '30d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">30D</a>
            <a href="{{ route('dashboard', ['range' => '90d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '90d' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">90D</a>
            <a href="{{ route('dashboard', ['range' => '1y']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === '1y' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">1A</a>
            <a href="{{ route('dashboard', ['range' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === 'all' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">Todo</a>
            <button onclick="document.getElementById('customRange').classList.toggle('hidden')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $range === 'custom' ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface-variant hover:bg-primary-container/20' }}">
              <span class="material-symbols-outlined text-[14px]">calendar_month</span>
            </button>
          </div>
        </div>
        <form id="customRange" method="GET" action="{{ route('dashboard') }}" class="{{ $range === 'custom' ? '' : 'hidden' }} flex items-center gap-3 mb-4 px-1">
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
          $points = $chartData->pluck('net')->values();
          $count = max($points->count(), 2);
          $min = min(0, $points->min());
          $max = max(1, $points->max());
          $range = $max - $min;
          $chartW = 800;
          $chartH = 220;
          $padding = 55;
          $plotH = $chartH - $padding * 2;
          $plotW = $chartW - $padding * 2;

          $coords = $points->map(fn($v, $i) => [
            'x'        => round($padding + ($i / max($count - 1, 1)) * $plotW),
            'y'        => round($padding + $plotH - (($v - $min) / $range) * $plotH),
            'date'     => $chartData[$i]->date->format('M d'),
            'income'   => number_format($chartData[$i]->income, 2),
            'expenses' => number_format($chartData[$i]->expenses, 2),
            'net'      => number_format($chartData[$i]->net, 2),
          ]);

          $pathSegments = [];
          $first = true;
          foreach ($coords as $c) {
            $pathSegments[] = $first ? "M {$c['x']} {$c['y']}" : "L {$c['x']} {$c['y']}";
            $first = false;
          }
          $pathD = implode(' ', $pathSegments);
          $lastCoord = $coords->last();
          $areaD = $pathD . " L {$lastCoord['x']} {$chartH} L {$padding} {$chartH} Z";

          $yLabels = [];
          $steps = 4;
          for ($i = 0; $i <= $steps; $i++) {
            $val = $min + ($range * $i / $steps);
            $yLabels[] = [
              'y'     => round($padding + $plotH - ($i / $steps) * $plotH),
              'label' => ($val >= 0 ? '+' : '') . number_format($val, 0),
            ];
          }
        @endphp
        <div class="relative flex-grow" id="chartContainer">
          <svg class="w-full h-full overflow-visible" viewBox="0 0 {{ $chartW }} {{ $chartH }}" id="balanceChart">
            <defs>
              <linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#2563eb" stop-opacity="0.2"/>
                <stop offset="100%" stop-color="#2563eb" stop-opacity="0"/>
              </linearGradient>
            </defs>
            @foreach($yLabels as $l)
              <text x="{{ $padding - 8 }}" y="{{ $l['y'] + 4 }}" text-anchor="end" fill="#737686" font-size="10" font-family="Inter,sans-serif">{{ $l['label'] }}</text>
              <line stroke="#E2E8F0" stroke-dasharray="3,3" x1="{{ $padding }}" x2="{{ $chartW }}" y1="{{ $l['y'] }}" y2="{{ $l['y'] }}"/>
            @endforeach
            <line stroke="#CBD5E1" x1="{{ $padding }}" x2="{{ $chartW }}" y1="{{ $chartH - $padding }}" y2="{{ $chartH - $padding }}"/>
            <path d="{{ $areaD }}" fill="url(#chartGradient)"/>
            <path d="{{ $pathD }}" fill="none" stroke="#004ac6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
            @foreach($coords as $i => $c)
              <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" fill="#004ac6" r="4" stroke="white" stroke-width="2"
                      data-index="{{ $i }}" data-date="{{ $c['date'] }}" data-income="{{ $c['income'] }}"
                      data-expenses="{{ $c['expenses'] }}" data-net="{{ $c['net'] }}"
                      class="chart-point cursor-pointer"
                      onmouseenter="highlightPoint({{ $i }}, true)" onmouseleave="highlightPoint({{ $i }}, false)"/>
            @endforeach
            <rect x="{{ $padding }}" y="0" width="{{ $plotW }}" height="{{ $chartH }}" fill="transparent"
                  onmousemove="trackChartMove(event)" onmouseleave="hideChartTooltip()"/>
          </svg>
          <div id="chartTooltip" class="hidden absolute pointer-events-none z-10 bg-inverse-surface text-inverse-on-surface px-3 py-2 rounded-lg shadow-xl text-xs whitespace-nowrap">
            <div id="ttDate" class="font-semibold mb-1"></div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
              <span>Ingresos:</span><span id="ttIncome" class="text-right font-medium text-tertiary"></span>
              <span>Egresos:</span><span id="ttExpenses" class="text-right font-medium text-error"></span>
              <span class="font-semibold">Neto:</span><span id="ttNet" class="text-right font-bold"></span>
            </div>
          </div>
          <div class="absolute top-2 right-4 bg-inverse-surface text-inverse-on-surface px-3 py-1.5 rounded text-xs shadow-xl pointer-events-none font-medium">
            {{ $chartData->last()->date->format('M d') }}: <strong>${{ number_format($chartData->last()->net, 2) }}</strong>
          </div>
        </div>
        @push('scripts')
        <script>
        var chartPoints = @json($coords);
        function highlightPoint(idx, show) {
          var pts = document.querySelectorAll('.chart-point');
          pts.forEach(function(p) { p.setAttribute('r', '4'); p.setAttribute('stroke-width', '2'); });
          if (!show) { hideChartTooltip(); return; }
          var pt = pts[idx]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          positionTooltip(pt, idx);
        }
        function trackChartMove(e) {
          var svg = document.getElementById('balanceChart');
          var rect = svg.getBoundingClientRect();
          var svgX = e.clientX - rect.left;
          var svgW = rect.width;
          var viewW = {{ $chartW }};
          var chartX = (svgX / svgW) * viewW;
          var closest = 0, minDist = Infinity;
          chartPoints.forEach(function(p, i) {
            var d = Math.abs(p.x - chartX);
            if (d < minDist) { minDist = d; closest = i; }
          });
          var pts = document.querySelectorAll('.chart-point');
          pts.forEach(function(p) { p.setAttribute('r', '4'); p.setAttribute('stroke-width', '2'); });
          var pt = pts[closest]; if (!pt) return;
          pt.setAttribute('r', '7'); pt.setAttribute('stroke-width', '3');
          positionTooltip(pt, closest);
        }
        function positionTooltip(pt, idx) {
          var tooltip = document.getElementById('chartTooltip');
          var container = document.getElementById('chartContainer');
          var svg = document.getElementById('balanceChart');
          var svgRect = svg.getBoundingClientRect();
          var contRect = container.getBoundingClientRect();
          var scaleX = svgRect.width / {{ $chartW }};
          var scaleY = svgRect.height / {{ $chartH }};
          var cx = parseFloat(pt.getAttribute('cx')) * scaleX;
          var cy = parseFloat(pt.getAttribute('cy')) * scaleY;
          document.getElementById('ttDate').textContent = chartPoints[idx].date;
          document.getElementById('ttIncome').textContent = '$' + chartPoints[idx].income;
          document.getElementById('ttExpenses').textContent = '$' + chartPoints[idx].expenses;
          document.getElementById('ttNet').textContent = '$' + chartPoints[idx].net;
          var left = cx - tooltip.offsetWidth / 2;
          var top = cy - tooltip.offsetHeight - 10;
          if (top < 0) top = cy + 10;
          if (left < 0) left = 4;
          if (left + tooltip.offsetWidth > contRect.width - 4) left = contRect.width - tooltip.offsetWidth - 4;
          tooltip.style.left = Math.round(left) + 'px';
          tooltip.style.top = Math.round(top) + 'px';
          tooltip.classList.remove('hidden');
        }
        function hideChartTooltip() {
          document.getElementById('chartTooltip').classList.add('hidden');
        }
        </script>
        @endpush
        @endif
      </div>

      {{-- Distribución de gastos mensual por categoría con barra de progreso y leyenda --}}
      <div class="bg-white p-card-padding rounded-xl shadow-md border border-outline-variant flex flex-col">
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-6">Distribuci&oacute;n de Gastos Mensual</h3>
        <div class="flex-grow flex flex-col space-y-4">
          @php
            $catTotal = $categoryExpenses->sum('total');
            $colors = ['bg-primary', 'bg-tertiary-container', 'bg-secondary', 'bg-error', 'bg-outline'];
          @endphp
          @if($catTotal > 0)
            <div class="relative w-full h-4 bg-surface-container-high rounded-full overflow-hidden flex">
              @foreach($categoryExpenses as $i => $cat)
                <div class="h-full {{ $colors[$i % count($colors)] }}" style="width: {{ round(($cat->total / $catTotal) * 100) }}%;" title="{{ $cat->category }}"></div>
              @endforeach
            </div>
            <div class="w-full space-y-3">
              @foreach($categoryExpenses as $i => $cat)
                <div class="flex justify-between items-center">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full {{ $colors[$i % count($colors)] }}"></div>
                    <span class="font-body-md text-body-md">{{ $cat->category }}</span>
                  </div>
                  <span class="font-numeric-md text-numeric-md">${{ number_format($cat->total, 2) }}</span>
                </div>
              @endforeach
            </div>
          @else
            <div class="flex flex-col items-center justify-center py-8 text-on-surface-variant">
              <span class="material-symbols-outlined text-[48px] mb-2">pie_chart</span>
              <p class="text-body-md">Sin gastos registrados este mes</p>
              <p class="text-label-md mt-1">Los gastos aparecer&aacute;n aqu&iacute; cuando registres movimientos.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Tabla de movimientos recientes con fecha, descripción, monto y tipo --}}
    <div class="bg-white rounded-xl shadow-md border border-outline-variant overflow-hidden">
      <div class="p-card-padding border-b border-outline-variant flex justify-between items-center">
        <h3 class="font-headline-sm text-headline-sm text-on-surface">Movimientos Recientes</h3>
        <a href="{{ route('movimientos') }}" class="text-primary font-bold hover:underline font-label-md">Ver todos</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-surface-container-low">
            <tr>
              <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase">Fecha</th>
              <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase">Descripci&oacute;n</th>
              <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase text-right">Monto</th>
              <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant uppercase text-center">Tipo</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-outline-variant">
            @forelse($recentTransactions as $t)
              <tr class="hover:bg-surface-container transition-colors">
                <td class="px-6 h-table-row-height font-body-md text-body-md">{{ $t->transaction_date->format('M d, Y') }}</td>
                <td class="px-6 h-table-row-height font-body-md text-body-md">{{ $t->description }}</td>
                <td class="px-6 h-table-row-height font-numeric-md text-numeric-md text-right {{ $t->type === 'credito' ? 'text-tertiary' : 'text-error' }}">
                  {{ $t->type === 'credito' ? '+' : '-' }}${{ number_format($t->amount, 2) }}
                </td>
                <td class="px-6 h-table-row-height text-center">
                  <span class="px-2 py-1 rounded {{ $t->type === 'credito' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-error' }} text-[11px] font-bold uppercase">
                    {{ $t->type === 'credito' ? 'Credito' : 'Debito' }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-4 text-center text-on-surface-variant">No hay movimientos recientes</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
