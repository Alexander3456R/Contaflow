<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Recuperar Contraseña - ContaFlow</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary":"#004ac6","on-primary":"#ffffff","on-surface":"#191c1e","on-surface-variant":"#434655","outline":"#737686","outline-variant":"#c3c6d7","error":"#ba1a1a","surface-container-low":"#f2f4f6","surface-container-high":"#e6e8ea","surface":"#f7f9fb"},borderRadius:{DEFAULT:"0.125rem",lg:"0.25rem",xl:"0.5rem",full:"0.75rem"},fontFamily:{"label-md":["Inter"],"body-md":["Inter"],"headline-md":["Inter"]},fontSize:{"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.05em",fontWeight:"600"}],"body-md":["14px",{lineHeight:"20px",fontWeight:"400"}],"headline-md":["24px",{lineHeight:"32px",letterSpacing:"-0.01em",fontWeight:"600"}]}}}}</script>
  <style>body{font-family:'Inter',sans-serif;background-color:#f7f9fb}.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;display:inline-block;line-height:1}.glass-card{background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);border:1px solid #e2e8f0}.input-focus-ring:focus{outline:none;border-color:#004ac6;box-shadow:0 0 0 2px rgba(0,74,198,0.2)}</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="fixed inset-0 z-[-1] overflow-hidden">
    <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-[10%] -right-[10%] w-[30%] h-[30%] bg-secondary/5 rounded-full blur-[100px]"></div>
  </div>
  <main class="w-full max-w-[480px]">
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary mb-4 shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings:'FILL'1;">lock_reset</span>
      </div>
      <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight mb-1">Recuperar Contraseña</h1>
      <p class="font-body-md text-body-md text-on-surface-variant">Ingresa tu correo para verificar tu identidad.</p>
    </div>
    <section class="glass-card rounded-xl p-8 md:p-10">
      {{-- Paso 1: formulario para ingresar el correo electrónico y verificar la identidad --}}
      <form action="{{ route('password.email') }}" class="space-y-6" method="POST">
        @csrf
        <div class="space-y-1.5">
          <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="email">Correo Electrónico</label>
          <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
            <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all placeholder:text-outline" id="email" name="email" placeholder="juan@empresa.com" required type="email" value="{{ old('email') }}"/>
          </div>
          @error('email')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
        <button class="w-full bg-primary hover:bg-on-primary-fixed-variant active:scale-[0.98] text-white font-label-md text-label-md py-4 px-6 rounded-lg shadow-md shadow-primary/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider" type="submit">
          Verificar Correo
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
      </form>
      <div class="mt-8 pt-8 border-t border-outline-variant text-center">
        <p class="font-body-md text-body-md text-on-surface-variant">
          <a class="text-primary hover:underline font-semibold inline-flex items-center gap-1" href="{{ route('login') }}">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Volver al inicio de sesión
          </a>
        </p>
      </div>
    </section>
  </main>
</body>
</html>
