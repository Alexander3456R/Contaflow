<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Verificar Identidad - ContaFlow</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries" nonce="{{ $cspNonce }}"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <script nonce="{{ $cspNonce }}">tailwind.config={darkMode:"class",theme:{extend:{colors:{"primary":"#004ac6","on-primary":"#ffffff","on-surface":"#191c1e","on-surface-variant":"#434655","outline":"#737686","outline-variant":"#c3c6d7","error":"#ba1a1a","error-container":"#ffdad6","tertiary":"#006242","surface-container-low":"#f2f4f6","surface-container-high":"#e6e8ea","surface":"#f7f9fb"},borderRadius:{DEFAULT:"0.125rem",lg:"0.25rem",xl:"0.5rem",full:"0.75rem"},fontFamily:{"label-md":["Inter"],"body-md":["Inter"],"headline-sm":["Inter"],"headline-md":["Inter"]},fontSize:{"label-md":["12px",{lineHeight:"16px",letterSpacing:"0.05em",fontWeight:"600"}],"body-md":["14px",{lineHeight:"20px",fontWeight:"400"}],"headline-sm":["20px",{lineHeight:"28px",fontWeight:"600"}],"headline-md":["24px",{lineHeight:"32px",letterSpacing:"-0.01em",fontWeight:"600"}]}}}}</script>
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
        <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings:'FILL'1;">verified_user</span>
      </div>
      <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight mb-1">Verifica tu Identidad</h1>
      <p class="font-body-md text-body-md text-on-surface-variant">Responde las preguntas de seguridad que configuraste.</p>
    </div>
    <section class="glass-card rounded-xl p-8 md:p-10">
      @error('answers')
        <div class="mb-4 bg-error-container text-error px-4 py-3 rounded-lg flex items-center gap-2 text-sm" role="alert">
          <span class="material-symbols-outlined">error</span>
          <span>{{ $message }}</span>
        </div>
      @enderror
      {{-- Paso 2: formulario para responder las preguntas de seguridad configuradas --}}
      <form action="{{ route('password.questions.verify') }}" class="space-y-6" method="POST">
        @csrf
        @foreach($answers as $i => $ans)
          <div class="space-y-1.5">
            <label class="font-label-md text-label-md text-on-surface-variant block ml-1 uppercase" for="answer_{{ $i + 1 }}">Pregunta {{ $i + 1 }}</label>
            <p class="font-body-md text-body-md text-on-surface font-semibold mb-2">{{ $ans->question->question }}</p>
            <input name="answer_{{ $i + 1 }}" id="answer_{{ $i + 1 }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface input-focus-ring transition-all" placeholder="Tu respuesta" required type="text"/>
          </div>
        @endforeach
        <button class="w-full bg-primary hover:bg-on-primary-fixed-variant active:scale-[0.98] text-white font-label-md text-label-md py-4 px-6 rounded-lg shadow-md shadow-primary/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider" type="submit">
          Verificar Respuestas
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
      </form>
      <div class="mt-6 text-center">
        <a class="text-primary hover:underline font-semibold text-sm inline-flex items-center gap-1" href="{{ route('password.request') }}">
          <span class="material-symbols-outlined text-[16px]">arrow_back</span>
          Volver
        </a>
      </div>
    </section>
    <footer class="mt-12 text-center">
      <p class="font-label-md text-label-md text-outline uppercase tracking-widest">ContaFlow</p>
      <p class="font-label-md text-label-md text-outline/60 text-[10px] tracking-normal">Contabilidad personal simplificada</p>
      <p class="font-body-md text-body-md text-outline">&copy; {{ date('Y') }} &middot; Diseñado por Christopher Revelo</p>
    </footer>
  </main>
</body>
</html>
