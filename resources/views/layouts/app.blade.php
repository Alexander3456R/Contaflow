<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>@yield('title', 'ContaFlow')</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <script nonce="{{ $cspNonce }}">if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark');</script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries" nonce="{{ $cspNonce }}"></script>
  <script nonce="{{ $cspNonce }}">
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
  html.dark body, html.dark .bg-background { background-color: #0f1117; }
  html.dark .glass-card { background: rgba(30, 35, 50, 0.85); backdrop-filter: blur(10px); }
  html.dark .bg-surface-container-lowest { background-color: #1a1d27; }
  html.dark .bg-surface-container-low { background-color: #232733; }
  html.dark .bg-surface-container { background-color: #2a2f3d; }
  html.dark .bg-surface-container-high { background-color: #323846; }
  html.dark .bg-surface-container-highest { background-color: #3b4252; }
  html.dark .bg-surface-dim { background-color: #1a1d27; }
  html.dark .bg-surface-bright { background-color: #2a2f3d; }
  html.dark .text-on-surface { color: #e3e5ea; }
  html.dark .text-on-surface-variant { color: #b0b5c4; }
  html.dark .text-outline { color: #8a90a0; }
  html.dark .border-outline-variant { border-color: #323846; }
  html.dark .border-slate-200 { border-color: #323846; }
  html.dark .bg-white { background-color: #1a1d27; }
  html.dark .bg-white\/80 { background-color: rgba(26, 29, 39, 0.85); }
  html.dark .bg-black\/50 { background-color: rgba(0, 0, 0, 0.7); }
  html.dark .divide-outline-variant > * { border-color: #323846; }
  html.dark .hover\:bg-surface-container-low:hover { background-color: #232733; }
  html.dark .hover\:bg-surface-container-high:hover { background-color: #323846; }
  html.dark .hover\:bg-outline-variant:hover { background-color: #323846; }
  html.dark .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.3); }
  html.dark .shadow-sm { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px -1px rgba(0, 0, 0, 0.3); }
  html.dark .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.4); }
  html.dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.35), 0 4px 6px -4px rgba(0, 0, 0, 0.35); }
  html.dark .shadow-primary\/20 { box-shadow: 0 10px 15px -3px rgba(0, 74, 198, 0.25), 0 4px 6px -4px rgba(0, 74, 198, 0.2); }
  html.dark .shadow-primary\/30 { box-shadow: 0 10px 15px -3px rgba(0, 74, 198, 0.35), 0 4px 6px -4px rgba(0, 74, 198, 0.3); }
  html.dark .bg-surface-container-low\/10 { background-color: rgba(35, 39, 51, 0.1); }
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-track { background: #f1f1f1; }
  html.dark ::-webkit-scrollbar-track { background: #232733; }
  ::-webkit-scrollbar-thumb { background: #c3c6d7; border-radius: 10px; }
  html.dark ::-webkit-scrollbar-thumb { background: #4a5165; }
  ::-webkit-scrollbar-thumb:hover { background: #737686; }
  html.dark ::-webkit-scrollbar-thumb:hover { background: #6b7388; }
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
      <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ $isActive('perfil') }}">
        <span class="material-symbols-outlined">account_circle</span>
        <span class="font-body-md">Perfil</span>
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
        <h2 class="font-headline-md text-headline-md text-primary flex items-center gap-2">@yield('page-title', 'ContaFlow')</h2>
      </div>
      <div class="flex items-center gap-4">
        <div class="hidden sm:flex items-center px-3 py-1 bg-surface-container-low rounded-full text-on-surface-variant">
          <span class="material-symbols-outlined text-[18px] mr-2">calendar_today</span>
          <span class="font-label-md text-label-md">{{ now()->format('M d, Y') }}</span>
        </div>
        {{-- Botón para alternar entre modo claro y oscuro --}}
        <button id="themeBtn" class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-low transition-colors" aria-label="Cambiar tema">
          <span id="darkIcon" class="material-symbols-outlined">dark_mode</span>
          <span id="lightIcon" class="material-symbols-outlined hidden">light_mode</span>
        </button>
        {{-- Campana de notificaciones con contador de no leídas y menú desplegable --}}
        <div class="relative" id="notifContainer">
          <button id="notifBtn" class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-low transition-colors relative" aria-label="Notificaciones">
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
        <div class="relative" id="avatarContainer">
          <button id="avatarBtn" class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white text-sm font-bold hover:opacity-80 transition-opacity cursor-pointer">
            {{ substr(Auth::user()->name, 0, 1) }}
          </button>
          <div id="avatarDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-outline-variant z-50 overflow-hidden">
            <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-container-low text-sm transition-colors">
              <span class="material-symbols-outlined text-[18px]">settings</span>
              <span>Ajustes</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-surface-container-low text-sm text-error transition-colors">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span>Cerrar Sesión</span>
              </button>
            </form>
          </div>
        </div>
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
      <a href="{{ route('trazabilidad') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom('trazabilidad') }}">
        <span class="material-symbols-outlined">history</span>
        <span class="text-[10px]">Trazabilidad</span>
      </a>
      <a href="{{ route('perfil') }}" class="flex flex-col items-center gap-0.5 {{ $isActiveBottom('perfil') }}">
        <span class="material-symbols-outlined">account_circle</span>
        <span class="text-[10px]">Perfil</span>
      </a>
    </nav>
    <div class="h-16 md:hidden"></div>
  </main>

  {{-- (mobile Menu eliminado — se usa nav inferior + avatar dropdown) --}}

  {{-- Scripts para alternar tema oscuro/claro, notificaciones y cerrar el menú desplegable al hacer clic fuera --}}
  <script nonce="{{ $cspNonce }}">
  function toggleTheme() {
    var html = document.documentElement;
    var isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    document.getElementById('darkIcon').classList.toggle('hidden', isDark);
    document.getElementById('lightIcon').classList.toggle('hidden', !isDark);
  }
  (function() {
    var isDark = document.documentElement.classList.contains('dark');
    var di = document.getElementById('darkIcon');
    var li = document.getElementById('lightIcon');
    if (di && li) { di.classList.toggle('hidden', isDark); li.classList.toggle('hidden', !isDark); }
  })();
  function toggleAvatar() {
    document.getElementById('avatarDropdown').classList.toggle('hidden');
  }
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
    var nc = document.getElementById('notifContainer');
    if (nc && !nc.contains(e.target)) {
      document.getElementById('notifDropdown')?.classList.add('hidden');
    }
    var ac = document.getElementById('avatarContainer');
    if (ac && !ac.contains(e.target)) {
      document.getElementById('avatarDropdown')?.classList.add('hidden');
    }
  });
  document.getElementById('themeBtn').addEventListener('click', toggleTheme);
  document.getElementById('notifBtn').addEventListener('click', toggleNotif);
  document.getElementById('avatarBtn').addEventListener('click', toggleAvatar);
  </script>
  @stack('scripts')
</body>
</html>
