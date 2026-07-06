<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ContaFlow - Iniciar Sesión</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries" nonce="{{ $cspNonce }}"></script>
  <script nonce="{{ $cspNonce }}">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "on-secondary-container": "#5c647a", "background": "#f7f9fb",
          "tertiary-container": "#007d55", "outline": "#737686",
          "surface-container-highest": "#e0e3e5", "inverse-on-surface": "#eff1f3",
          "tertiary-fixed": "#6ffbbe", "surface": "#f7f9fb",
          "secondary-fixed-dim": "#bec6e0", "surface-dim": "#d8dadc",
          "primary-fixed-dim": "#b4c5ff", "surface-container-lowest": "#ffffff",
          "secondary-fixed": "#dae2fd", "secondary-container": "#dae2fd",
          "error-container": "#ffdad6", "primary-container": "#2563eb",
          "secondary": "#565e74", "on-secondary": "#ffffff",
          "on-tertiary": "#ffffff", "surface-variant": "#e0e3e5",
          "surface-container-low": "#f2f4f6", "on-tertiary-fixed-variant": "#005236",
          "tertiary-fixed-dim": "#4edea3", "on-error": "#ffffff",
          "surface-bright": "#f7f9fb", "on-primary-container": "#eeefff",
          "on-primary-fixed-variant": "#003ea8", "on-background": "#191c1e",
          "error": "#ba1a1a", "inverse-primary": "#b4c5ff",
          "tertiary": "#006242", "surface-tint": "#0053db",
          "inverse-surface": "#2d3133", "primary-fixed": "#dbe1ff",
          "on-primary-fixed": "#00174b", "primary": "#004ac6",
          "on-surface-variant": "#434655", "on-surface": "#191c1e",
          "outline-variant": "#c3c6d7", "surface-container": "#eceef0",
          "surface-container-high": "#e6e8ea", "on-tertiary-container": "#bdffdb",
          "on-primary": "#ffffff", "on-tertiary-fixed": "#002113",
          "on-secondary-fixed-variant": "#3f465c", "on-secondary-fixed": "#131b2e",
          "on-error-container": "#93000a"
        },
        borderRadius: { DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem" },
        spacing: { "margin-page": "2rem", gutter: "1.5rem", "table-row-height": "3rem", unit: "4px", "card-padding": "1.25rem" },
        fontFamily: {
          "headline-md-mobile": ["Inter"], "body-md": ["Inter"], "numeric-md": ["Inter"],
          "headline-md": ["Inter"], "headline-sm": ["Inter"], "display-lg": ["Inter"],
          "body-lg": ["Inter"], "label-md": ["Inter"]
        },
        fontSize: {
          "headline-md-mobile": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "body-md": ["14px", {lineHeight: "20px", fontWeight: "400"}],
          "numeric-md": ["14px", {lineHeight: "20px", fontWeight: "500"}],
          "headline-md": ["24px", {lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600"}],
          "headline-sm": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "display-lg": ["36px", {lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700"}],
          "body-lg": ["16px", {lineHeight: "24px", fontWeight: "400"}],
          "label-md": ["12px", {lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600"}]
        }
      }
    }
  }
  </script>
  <style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
  body { font-family: 'Inter', sans-serif; }
  .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
  </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex items-center justify-center p-4">
  <main class="w-full max-w-[480px] space-y-8 animate-in fade-in duration-700">
    <div class="text-center space-y-4">
      <div class="flex items-center justify-center gap-3">
        <span class="material-symbols-outlined text-primary text-[40px]" style="font-variation-settings: 'FILL' 1;">account_balance</span>
        <h1 class="font-headline-md text-headline-md font-bold tracking-tight text-primary">ContaFlow</h1>
      </div>
      <h2 class="font-headline-sm text-headline-sm text-on-surface">Bienvenido de nuevo</h2>
      <p class="font-body-md text-body-md text-on-surface-variant">Ingresa tus credenciales para acceder a tu panel contable.</p>
    </div>
    @if(session('status'))
      <div class="bg-tertiary-container/20 text-tertiary font-body-md text-body-md p-4 rounded-lg text-center" role="alert">{{ session('status') }}</div>
    @endif
    <div class="bg-surface-container-lowest border border-outline-variant shadow-sm rounded-xl p-8 space-y-6">
      {{-- Formulario de inicio de sesión con email, contraseña y opción "recordarme" --}}
      <form class="space-y-5" id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-2">
          <label class="font-label-md text-label-md text-on-surface-variant uppercase" for="email">Correo Electrónico</label>
          <div class="relative group">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">mail</span>
            <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md placeholder:text-outline-variant" id="email" placeholder="ejemplo@contaflow.com" required="" type="email" name="email" value="{{ old('email') }}">
          </div>
          @error('email')
            <p class="text-error text-sm mt-1" role="alert">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-2">
          <div class="flex justify-between items-center">
            <label class="font-label-md text-label-md text-on-surface-variant uppercase" for="password">Contraseña</label>
            <a class="font-label-md text-label-md text-primary hover:underline transition-all" href="{{ route('password.request') }}">¿Olvidé mi contraseña?</a>
          </div>
          <div class="relative group">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">lock</span>
            <input class="w-full pl-10 pr-12 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md placeholder:text-outline-variant" id="password" placeholder="••••••••" required="" type="password" name="password">
            <button id="passwordToggleBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline-variant hover:text-on-surface-variant transition-colors" type="button" aria-label="Mostrar u ocultar contraseña">
              <span class="material-symbols-outlined" id="passwordIcon">visibility</span>
            </button>
          </div>
          @error('password')
            <p class="text-error text-sm mt-1" role="alert">{{ $message }}</p>
          @enderror
        </div>
        <div class="flex items-center gap-2">
          <input class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary" id="remember" type="checkbox" name="remember">
          <label class="font-body-md text-body-md text-on-surface-variant cursor-pointer" for="remember">Recordarme</label>
        </div>
        <button class="w-full bg-primary-container text-on-primary-container py-3 rounded-lg font-headline-sm text-headline-sm hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2" type="submit">
          Iniciar Sesión
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </form>
    </div>
    <div class="text-center">
      <p class="font-body-md text-body-md text-on-surface-variant">
        ¿No tienes una cuenta?&nbsp;<a href="{{ route('register') }}" class="text-primary font-bold hover:underline transition-all">Crear una cuenta</a>
      </p>
    </div>
  </main>
  <script nonce="{{ $cspNonce }}">
  function togglePassword() {
    var passwordInput = document.getElementById('password');
    var passwordIcon = document.getElementById('passwordIcon');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      passwordIcon.innerText = 'visibility_off';
    } else {
      passwordInput.type = 'password';
      passwordIcon.innerText = 'visibility';
    }
  }
  document.getElementById('passwordToggleBtn').addEventListener('click', togglePassword);
  </script>
</body>
</html>
