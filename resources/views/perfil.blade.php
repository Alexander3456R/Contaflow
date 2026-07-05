@extends('layouts.app')

@section('title', 'ContaFlow - Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')
<div class="p-6 md:p-8 space-y-6 max-w-5xl mx-auto">
  @if(session('success'))
  <div class="mb-6 p-4 bg-tertiary/10 border border-tertiary/20 rounded-xl text-tertiary font-medium flex items-center gap-3">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
  </div>
  @endif

  <section class="mb-gutter">
    <div class="bg-surface-container-lowest shadow rounded-xl overflow-hidden border border-slate-200 p-card-padding">
      <div class="flex flex-col md:flex-row items-center gap-6">
        <div class="relative group">
          <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-primary-container flex items-center justify-center bg-primary-container text-on-primary-container text-4xl font-bold">
            {{ substr($user->name, 0, 1) }}
          </div>
          <button class="absolute bottom-1 right-1 bg-primary text-on-primary p-2 rounded-full shadow-lg hover:scale-105 transition-transform"><span class="material-symbols-outlined text-[20px]">edit</span></button>
        </div>
        <div class="text-center md:text-left flex-1">
          <h2 class="font-headline-md text-headline-md text-on-surface">{{ $user->name }}</h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant mb-2">{{ $user->email }}</p>
          <div class="flex flex-wrap justify-center md:justify-start gap-2">
            <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container rounded-full font-label-md text-label-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">verified</span> Verificada</span>
            <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full font-label-md text-label-md">ID: {{ $user->id }}</span>
          </div>
        </div>
        <div class="hidden md:block"><button onclick="document.getElementById('editModal').classList.remove('hidden')" class="px-6 py-2 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Editar Perfil</button></div>
      </div>
    </div>
  </section>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
    <div class="space-y-4">
      <h3 class="font-label-md text-label-md text-outline uppercase tracking-widest px-1">Configuración</h3>
      <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200 overflow-hidden">
        <button class="w-full flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors border-b border-outline-variant" onclick="document.getElementById('editModal').classList.remove('hidden')">
          <div class="flex items-center gap-4"><div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary"><span class="material-symbols-outlined">person</span></div><div class="text-left"><p class="font-headline-sm text-headline-sm text-[16px]">Datos Personales</p><p class="text-on-surface-variant text-[12px]">Nombre, email y contacto</p></div></div>
          <span class="material-symbols-outlined text-outline">chevron_right</span>
        </button>
        <button class="w-full flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors border-b border-outline-variant" onclick="document.getElementById('passwordSection').classList.toggle('hidden')">
          <div class="flex items-center gap-4"><div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary"><span class="material-symbols-outlined">lock</span></div><div class="text-left"><p class="font-headline-sm text-headline-sm text-[16px]">Seguridad</p><p class="text-on-surface-variant text-[12px]">Cambio de contraseña y 2FA</p></div></div>
          <span class="material-symbols-outlined text-outline">chevron_right</span>
        </button>
      </div>

      {{-- Sección de cambio de contraseña con validación en vivo --}}
      <div id="passwordSection" class="hidden bg-surface-container-lowest shadow rounded-xl border border-slate-200 p-card-padding">
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-4">Cambiar Contraseña</h3>
        <form method="POST" action="{{ route('perfil.password') }}">
          @csrf
          @method('PUT')
          <div class="space-y-4">
            <div>
              <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Contraseña Actual</label>
              <input type="password" name="current_password" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
            </div>
            <div>
              <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Nueva Contraseña</label>
              <input type="password" name="password" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
            </div>
            <div>
              <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Confirmar Contraseña</label>
              <input type="password" name="password_confirmation" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
            </div>
            <x-password-checklist password-id="password" confirm-id="password_confirmation" title="La nueva contraseña debe cumplir:"/>
            <button type="submit" class="w-full py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Actualizar Contraseña</button>
          </div>
        </form>
      </div>
    </div>

    <div class="space-y-4">
      <h3 class="font-label-md text-label-md text-outline uppercase tracking-widest px-1">Preferencias</h3>
      <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-outline-variant">
          <div class="flex items-center gap-4"><div class="w-10 h-10 rounded-lg bg-secondary-fixed flex items-center justify-center text-secondary"><span class="material-symbols-outlined">translate</span></div><div class="text-left"><p class="font-headline-sm text-headline-sm text-[16px]">Idioma</p><p class="text-on-surface-variant text-[12px]">Selecciona tu lenguaje</p></div></div>
          <select class="bg-surface border-slate-200 rounded-lg font-body-md text-on-surface focus:ring-primary focus:border-primary"><option selected value="es">Español</option><option value="en">English</option><option value="pt">Português</option></select>
        </div>
        <div class="flex items-center justify-between p-4">
          <div class="flex items-center gap-4"><div class="w-10 h-10 rounded-lg bg-secondary-fixed flex items-center justify-center text-secondary"><span class="material-symbols-outlined">payments</span></div><div class="text-left"><p class="font-headline-sm text-headline-sm text-[16px]">Moneda Predeterminada</p><p class="text-on-surface-variant text-[12px]">Para reportes y balances</p></div></div>
          <span class="px-3 py-1 bg-primary-container text-on-primary-container rounded-lg font-label-md text-label-md">USD</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal de edición de datos personales del perfil --}}
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4" onclick="if(event.target===this)this.classList.add('hidden')">
  <div class="bg-surface-container-lowest rounded-xl p-card-padding shadow-xl border border-outline-variant w-full max-w-md">
    <div class="flex justify-between items-center mb-6">
      <h3 class="font-headline-sm text-headline-sm text-on-surface">Editar Perfil</h3>
      <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="POST" action="{{ route('perfil.update') }}">
      @csrf
      @method('PUT')
      <div class="space-y-4">
        <div>
          <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Nombre</label>
          <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
        </div>
        <div>
          <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Email</label>
          <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 py-2.5 bg-surface-container-high text-on-surface font-label-md text-label-md rounded-lg hover:bg-outline-variant transition-colors">Cancelar</button>
          <button type="submit" class="flex-1 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
