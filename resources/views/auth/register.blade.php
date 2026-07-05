<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Crear Cuenta - ContaFlow</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "secondary-container": "#dae2fd", "surface-container-low": "#f2f4f6",
          "tertiary-container": "#007d55", "surface-container-high": "#e6e8ea",
          "primary": "#004ac6", "tertiary-fixed-dim": "#4edea3",
          "secondary": "#565e74", "on-tertiary-fixed": "#002113",
          "on-surface": "#191c1e", "on-secondary-container": "#5c647a",
          "surface-dim": "#d8dadc", "on-primary-container": "#eeefff",
          "tertiary": "#006242", "inverse-surface": "#2d3133",
          "surface-container-highest": "#e0e3e5", "inverse-on-surface": "#eff1f3",
          "on-tertiary-fixed-variant": "#005236", "primary-container": "#2563eb",
          "on-tertiary-container": "#bdffdb", "surface-variant": "#e0e3e5",
          "surface": "#f7f9fb", "on-surface-variant": "#434655",
          "on-secondary-fixed-variant": "#3f465c", "on-primary-fixed": "#00174b",
          "on-primary-fixed-variant": "#003ea8", "on-tertiary": "#ffffff",
          "inverse-primary": "#b4c5ff", "surface-tint": "#0053db",
          "on-error": "#ffffff", "tertiary-fixed": "#6ffbbe",
          "on-secondary-fixed": "#131b2e", "secondary-fixed": "#dae2fd",
          "surface-bright": "#f7f9fb", "error-container": "#ffdad6",
          "secondary-fixed-dim": "#bec6e0", "surface-container": "#eceef0",
          "primary-fixed": "#dbe1ff", "outline-variant": "#c3c6d7",
          "surface-container-lowest": "#ffffff", "background": "#f7f9fb",
          "on-secondary": "#ffffff", "outline": "#737686",
          "error": "#ba1a1a", "on-error-container": "#93000a",
          "on-background": "#191c1e", "on-primary": "#ffffff",
          "primary-fixed-dim": "#b4c5ff"
        },
        borderRadius: { DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem" },
        spacing: { "card-padding": "1.25rem", unit: "4px", "margin-page": "2rem", "table-row-height": "3rem", gutter: "1.5rem" },
        fontFamily: {
          "numeric-md": ["Inter"], "headline-sm": ["Inter"], "headline-md-mobile": ["Inter"],
          "display-lg": ["Inter"], "body-lg": ["Inter"], "label-md": ["Inter"],
          "headline-md": ["Inter"], "body-md": ["Inter"]
        },
        fontSize: {
          "numeric-md": ["14px", {lineHeight: "20px", fontWeight: "500"}],
          "headline-sm": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "headline-md-mobile": ["20px", {lineHeight: "28px", fontWeight: "600"}],
          "display-lg": ["36px", {lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700"}],
          "body-lg": ["16px", {lineHeight: "24px", fontWeight: "400"}],
          "label-md": ["12px", {lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600"}],
          "headline-md": ["24px", {lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600"}],
          "body-md": ["14px", {lineHeight: "20px", fontWeight: "400"}]
        }
      },
    },
  }
  </script>
  <style>
  body { font-family: 'Inter', sans-serif; background-color: #f7f9fb; }
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; display: inline-block; line-height: 1; }
  .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid #e2e8f0; }
  .input-focus-ring:focus { outline: none; border-color: #004ac6; box-shadow: 0 0 0 2px rgba(0, 74, 198, 0.2); }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="fixed inset-0 z-[-1] overflow-hidden">
    <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-[10%] -right-[10%] w-[30%] h-[30%] bg-secondary/5 rounded-full blur-[100px]"></div>
  </div>
  <main class="w-full max-w-[480px]">
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary mb-4 shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings: 'FILL' 1;">account_balance</span>
      </div>
      <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight mb-1">Únete a ContaFlow</h1>
      <p class="font-body-md text-body-md text-on-surface-variant">Gestiona tu contabilidad con excelencia.</p>
    </div>
    <section class="glass-card rounded-xl p-8 md:p-10">
      {{-- Formulario de registro con campos de nombre, email, contraseña y preguntas de seguridad --}}
      <form action="{{ route('register') }}" class="space-y-6" method="POST">
        @csrf
        <div class="space-y-1.5">
          <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="full_name">Nombre Completo</label>
          <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
            <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all placeholder:text-outline" id="full_name" name="name" placeholder="Juan Pérez" required="" type="text" value="{{ old('name') }}"/>
          </div>
          @error('name')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1.5">
          <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="email">Correo Electrónico</label>
          <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
            <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all placeholder:text-outline" id="email" name="email" placeholder="juan@empresa.com" required="" type="email" value="{{ old('email') }}"/>
          </div>
          @error('email')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
        {{-- Sección de preguntas de seguridad (3 preguntas seleccionables con sus respuestas) --}}
        <div class="border-t border-outline-variant pt-6">
          <h3 class="font-headline-sm text-headline-sm text-on-surface mb-4">Preguntas de Seguridad</h3>
          <p class="text-body-md text-on-surface-variant mb-4">Selecciona 3 preguntas y respóndelas. Las usarás para recuperar tu cuenta.</p>
          @for($i = 1; $i <= 3; $i++)
          <div class="space-y-1.5 mb-4">
            <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="question_{{ $i }}">Pregunta {{ $i }}</label>
            <select name="question_{{ $i }}" id="question_{{ $i }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md input-focus-ring transition-all" required>
              <option value="">Seleccionar pregunta...</option>
              @foreach($questions as $q)
                <option value="{{ $q->id }}" {{ old('question_'.$i) == $q->id ? 'selected' : '' }}>{{ $q->question }}</option>
              @endforeach
            </select>
            @error('question_'.$i)
              <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
            <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase mt-2" for="answer_{{ $i }}">Respuesta {{ $i }}</label>
            <input name="answer_{{ $i }}" id="answer_{{ $i }}" value="{{ old('answer_'.$i) }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md input-focus-ring transition-all" placeholder="Tu respuesta" required type="text"/>
            @error('answer_'.$i)
              <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
          @endfor
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="password">Contraseña</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
              <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all placeholder:text-outline" id="password" name="password" placeholder="••••••••" required="" type="password"/>
            </div>
            @error('password')
              <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1.5">
            <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="password_confirmation">Confirmar Contraseña</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock_reset</span>
              <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all placeholder:text-outline" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required="" type="password"/>
            </div>
          </div>
        </div>
        <x-password-checklist password-id="password" confirm-id="password_confirmation"/>
        <p class="font-label-md text-label-md text-on-surface-variant text-center px-4">
          Al crear una cuenta, aceptas nuestros
          <a class="text-primary hover:underline font-semibold" href="#">Términos de Servicio</a> y
          <a class="text-primary hover:underline font-semibold" href="#">Política de Privacidad</a>.
        </p>
        <button class="w-full bg-primary hover:bg-on-primary-fixed-variant active:scale-[0.98] text-white font-label-md text-label-md py-4 px-6 rounded-lg shadow-md shadow-primary/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider" type="submit">
          Crear Cuenta
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
      </form>
      <div class="mt-8 pt-8 border-t border-outline-variant text-center">
        <p class="font-body-md text-body-md text-on-surface-variant">
          ¿Ya tienes cuenta en ContaFlow?
          <a class="text-primary hover:text-on-primary-fixed-variant font-semibold transition-colors inline-flex items-center gap-1" href="{{ route('login') }}">
            Iniciar Sesión
            <span class="material-symbols-outlined text-[16px]">login</span>
          </a>
        </p>
      </div>
    </section>
    <footer class="mt-12 text-center">
      <p class="font-label-md text-label-md text-outline uppercase tracking-widest">
        Sistema de Contabilidad Profesional &copy; 2024 ContaFlow Inc.
      </p>
    </footer>
  </main>
  <script>
  document.querySelectorAll('input').forEach(function (input) {
    input.addEventListener('focus', function () {
      input.parentElement.classList.add('ring-2', 'ring-primary/10');
    });
    input.addEventListener('blur', function () {
      input.parentElement.classList.remove('ring-2', 'ring-primary/10');
    });
  });
  </script>
</body>
</html>
