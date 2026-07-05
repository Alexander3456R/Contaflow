<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>@yield('title', 'ContaFlow')</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          tertiary: "#006242", "primary-fixed-dim": "#b4c5ff", "surface-dim": "#d8dadc",
          "on-primary-fixed-variant": "#003ea8", "on-primary-fixed": "#00174b", "surface-tint": "#0053db",
          "secondary-container": "#dae2fd", "surface-container-lowest": "#ffffff", primary: "#004ac6",
          "tertiary-container": "#007d55", "on-secondary": "#ffffff", "primary-fixed": "#dbe1ff",
          "on-primary-container": "#eeefff", "primary-container": "#2563eb", "on-secondary-fixed": "#131b2e",
          "inverse-on-surface": "#eff1f3", "inverse-primary": "#b4c5ff", "on-background": "#191c1e",
          secondary: "#565e74", background: "#f7f9fb", "secondary-fixed-dim": "#bec6e0", outline: "#737686",
          "on-tertiary-container": "#bdffdb", "on-error-container": "#93000a", "on-secondary-fixed-variant": "#3f465c",
          "on-tertiary-fixed-variant": "#005236", "error-container": "#ffdad6", "surface-variant": "#e0e3e5",
          "surface-container-highest": "#e0e3e5", "secondary-fixed": "#dae2fd", "tertiary-fixed-dim": "#4edea3",
          "surface-container-high": "#e6e8ea", "inverse-surface": "#2d3133", "on-tertiary-fixed": "#002113",
          "on-secondary-container": "#5c647a", "on-primary": "#ffffff", error: "#ba1a1a", "on-surface": "#191c1e",
          "on-error": "#ffffff", "surface-bright": "#f7f9fb", "surface-container-low": "#f2f4f6",
          "outline-variant": "#c3c6d7", "tertiary-fixed": "#6ffbbe", "surface-container": "#eceef0",
          "on-surface-variant": "#434655", "on-tertiary": "#ffffff", surface: "#f7f9fb"
        },
        borderRadius: { DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem" },
        spacing: { unit: "4px", "margin-page": "2rem", gutter: "1.5rem", "card-padding": "1.25rem", "table-row-height": "3rem" },
        fontFamily: {
          "display-lg": ["Inter"], "label-md": ["Inter"], "body-md": ["Inter"],
          "headline-md-mobile": ["Inter"], "numeric-md": ["Inter"], "headline-sm": ["Inter"],
          "headline-md": ["Inter"], "body-lg": ["Inter"]
        },
        fontSize: {
          "display-lg": ["36px", {lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700"}],
          "label-md": ["12px", {lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600"}],
          "body-md": ["14px", {lineHeight: "20px", fontWeight: "400"}],
          "headline-md-mobile": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "numeric-md": ["14px", {lineHeight: "20px", fontWeight: "500"}],
          "headline-sm": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "headline-md": ["24px", {lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600"}],
          "body-lg": ["16px", {lineHeight: "24px", fontWeight: "400"}]
        }
      },
    },
  }
  </script>
  <style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
  body { font-family: 'Inter', sans-serif; background-color: #f7f9fb; }
  .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-track { background: #f1f1f1; }
  ::-webkit-scrollbar-thumb { background: #c3c6d7; border-radius: 10px; }
  ::-webkit-scrollbar-thumb:hover { background: #737686; }
  </style>
  @stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md">
  @php
  $route = request()->route()->getName();
  $isActive = fn($names) => in_array($route, (array) $names) ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-high' : 'text-on-surface-variant hover:bg-surface-container-high';
  $isActiveBottom = fn($names) => in_array($route, (array) $names) ? 'text-primary font-bold' : 'text-on-surface-variant';
  $unreadCount = \App\Models\AuditLog::where('user_id', Auth::id())->whereNull('read_at')->count();
  $notifications = \App\Models\AuditLog::where('user_id', Auth::id())->latest()->take(5)->get();
  @endphp

  {{-- Barra lateral de navegación principal con enlaces a Dashboard, Movimientos, Reportes y Trazabilidad --}}
  <aside class="fixed left-0 top-0 h-full w-[280px] bg-surface-container-lowest border-r border-outline-variant shadow-md flex flex-col py-8 z-50 hidden md:flex">
    <div class="px-6 mb-10"><h1 class="font-display-lg text-display-lg text-primary flex items-center gap-2"><span class="material-symbols-outlined text-[36px]">account_balance</span>ContaFlow</h1></div>
    <nav class="flex-1 space-y-1 px-4">
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ $isActive('dashboard') }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="font-body-md">Dashboard</span>
      </a>
      <a href="{{ route('movimientos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ $isActive(['movimientos', 'movimientos.store', 'movimientos.update', 'movimientos.destroy']) }}">
        <span class="material-symbols-outlined">swap_horiz</span>
        <span class="font-body-md">Movimientos</span>
      </a>
      <a href="{{ route('reportes') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ $isActive('reportes') }}">
        <span class="material-symbols-outlined">assessment</span>
        <span class="font-body-md">Reportes</span>
      </a>
      <a href="{{ route('trazabilidad') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ $isActive('trazabilidad') }}">
        <span class="material-symbols-outlined">history</span>
        <span class="font-body-md">Trazabilidad</span>
      </a>
    </nav>
    <div class="mt-auto px-6 pt-6 border-t border-outline-variant">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-white font-bold flex-shrink-0">{{ substr(Auth::user()->name, 0, 1) }}</div>
        <div class="flex-1 min-w-0">
          <a href="{{ route('perfil') }}" class="font-label-md text-on-surface font-bold truncate block">{{ Auth::user()->name }}</a>
          <span class="font-label-md text-on-surface-variant text-[10px]">Contador</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
          @csrf
          <button type="submit" class="text-on-surface-variant hover:text-error transition-colors" aria-label="Cerrar sesión">
            <span class="material-symbols-outlined">logout</span>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <main class="md:ml-[280px] min-h-screen flex flex-col">
    {{-- Encabezado superior con título de página, fecha, campana de notificaciones y avatar de usuario --}}
    <header class="sticky top-0 z-40 w-full bg-white/80 backdrop-blur-md border-b border-outline-variant shadow-sm flex justify-between items-center px-6 h-16">
      <div class="flex items-center gap-4">
        <button class="md:hidden p-2 text-on-surface-variant active:scale-95" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" aria-label="Menú">
          <span class="material-symbols-outlined">menu</span>
        </button>
        <h2 class="font-headline-md text-headline-md text-primary">@yield('page-title', 'ContaFlow')</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="hidden sm:flex items-center px-3 py-1 bg-surface-container-low rounded-full text-on-surface-variant">
          <span class="material-symbols-outlined text-[18px] mr-2">calendar_today</span>
          <span class="font-label-md text-label-md">{{ now()->format('M d, Y') }}</span>
        </div>
        {{-- Campana de notificaciones con contador de no leídas y menú desplegable --}}
        <div class="relative" id="notifContainer">
          <button onclick="toggleNotif()" class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-low transition-colors relative" aria-label="Notificaciones">
            <span class="material-symbols-outlined">notifications</span>
            @if($unreadCount > 0)
              <span class="absolute top-1 right-1 w-4 h-4 bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
            @endif
          </button>
          <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-outline-variant z-50">
            <div class="px-4 py-3 border-b border-outline-variant font-bold text-sm">Notificaciones</div>
            <div class="max-h-64 overflow-y-auto">
              @forelse($notifications as $n)
                <div class="px-4 py-3 border-b border-outline-variant hover:bg-surface-container-low text-sm">
                  <p class="font-medium">{{ $n->description }}</p>
                  <p class="text-xs text-on-surface-variant mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                </div>
              @empty
                <div class="px-4 py-6 text-center text-on-surface-variant text-sm">Sin notificaciones recientes</div>
              @endforelse
            </div>
          </div>
        </div>
        <a href="{{ route('perfil') }}" class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white text-sm font-bold hover:opacity-80 transition-opacity">
          {{ substr(Auth::user()->name, 0, 1) }}
        </a>
      </div>
    </header>

    @if(session('success'))
      <div class="mx-6 mt-4 bg-tertiary-container/10 text-tertiary px-4 py-3 rounded-lg flex items-center gap-2" role="alert">
        <span class="material-symbols-outlined">check_circle</span>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if(session('status'))
      <div class="mx-6 mt-4 bg-primary-container/10 text-primary px-4 py-3 rounded-lg flex items-center gap-2" role="alert">
        <span class="material-symbols-outlined">info</span>
        <span>{{ session('status') }}</span>
      </div>
    @endif

    <div class="flex-1 flex flex-col">
      @yield('content')
    </div>

    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-outline-variant flex justify-around items-center h-16 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-50">
      <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom('dashboard') }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px]">Dashboard</span>
      </a>
      <a href="{{ route('movimientos') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom(['movimientos', 'movimientos.store', 'movimientos.update', 'movimientos.destroy']) }}">
        <span class="material-symbols-outlined">swap_horiz</span>
        <span class="text-[10px]">Movimientos</span>
      </a>
      <a href="{{ route('reportes') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom('reportes') }}">
        <span class="material-symbols-outlined">assessment</span>
        <span class="text-[10px]">Reportes</span>
      </a>
      <a href="{{ route('perfil') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom('perfil') }}">
        <span class="material-symbols-outlined">account_circle</span>
        <span class="text-[10px]">Perfil</span>
      </a>
    </nav>
    <div class="h-16 md:hidden"></div>
  </main>

  {{-- Menú móvil superpuesto para navegación en pantallas pequeñas --}}
  <div id="mobileMenu" class="hidden fixed inset-0 z-50 md:hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('mobileMenu').classList.add('hidden')"></div>
    <aside class="absolute left-0 top-0 h-full w-[280px] bg-surface-container-lowest border-r border-outline-variant shadow-xl flex flex-col py-8 z-50">
      <div class="px-6 mb-10 flex justify-between items-center">
        <h1 class="font-display-lg text-display-lg text-primary flex items-center gap-2"><span class="material-symbols-outlined text-[36px]">account_balance</span>ContaFlow</h1>
        <button onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface" aria-label="Cerrar menú">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <nav class="flex-1 space-y-1 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $isActive('dashboard') }}">
          <span class="material-symbols-outlined">dashboard</span>
          <span class="font-body-md">Dashboard</span>
        </a>
        <a href="{{ route('movimientos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $isActive(['movimientos', 'movimientos.store', 'movimientos.update', 'movimientos.destroy']) }}">
          <span class="material-symbols-outlined">swap_horiz</span>
          <span class="font-body-md">Movimientos</span>
        </a>
        <a href="{{ route('reportes') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $isActive('reportes') }}">
          <span class="material-symbols-outlined">assessment</span>
          <span class="font-body-md">Reportes</span>
        </a>
        <a href="{{ route('trazabilidad') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $isActive('trazabilidad') }}">
          <span class="material-symbols-outlined">history</span>
          <span class="font-body-md">Trazabilidad</span>
        </a>
        <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ $isActive('perfil') }}">
          <span class="material-symbols-outlined">account_circle</span>
          <span class="font-body-md">Perfil</span>
        </a>
      </nav>
      <div class="mt-auto px-6 pt-6 border-t border-outline-variant">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/10 rounded-lg transition-all">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-body-md">Cerrar Sesión</span>
          </button>
        </form>
      </div>
    </aside>
  </div>

  {{-- Scripts para alternar notificaciones y cerrar el menú desplegable al hacer clic fuera --}}
  <script>
  function toggleNotif() {
    var d = document.getElementById('notifDropdown');
    d.classList.toggle('hidden');
    if (!d.classList.contains('hidden')) {
      fetch('{{ route('notificaciones.leer') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
      var b = d.parentElement.querySelector('.bg-error');
      if (b) { b.remove(); }
    }
  }
  document.addEventListener('click', function(e) {
    var c = document.getElementById('notifContainer');
    if (c && !c.contains(e.target)) {
      document.getElementById('notifDropdown')?.classList.add('hidden');
    }
  });
  </script>
  @stack('scripts')
</body>
</html>
