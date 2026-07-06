@extends('layouts.app')

@section('title', 'ContaFlow - Mi Perfil')
@section('page-title')
<span class="material-symbols-outlined">account_circle</span> Mi Perfil
@endsection

@section('content')
<div class="p-6 md:p-8 max-w-3xl mx-auto space-y-6">
  @if(session('success'))
  <div class="p-4 bg-tertiary/10 border border-tertiary/20 rounded-xl text-tertiary font-medium flex items-center gap-3">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
  </div>
  @endif

  {{-- Avatar y datos principales --}}
  <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200 p-6">
    <div class="flex flex-col md:flex-row items-center gap-6">
      <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container text-4xl font-bold flex-shrink-0">
        {{ substr($user->name, 0, 1) }}
      </div>
      <div class="text-center md:text-left flex-1 min-w-0">
        <h2 class="font-headline-md text-headline-md text-on-surface">{{ $user->name }}</h2>
        <p class="text-on-surface-variant">{{ $user->email }}</p>
      </div>
    </div>
  </div>

  {{-- Editar Perfil --}}
  <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200">
    <button onclick="document.getElementById('editSection').classList.toggle('hidden')" class="w-full flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">person</span>
        </div>
        <div class="text-left">
          <p class="font-medium">Editar Perfil</p>
          <p class="text-on-surface-variant text-sm">Nombre, email y datos personales</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-outline">expand_more</span>
    </button>
    <div id="editSection" class="hidden border-t border-outline-variant p-4">
      <form method="POST" action="{{ route('perfil.update') }}">
        @csrf
        @method('PUT')
        <div class="space-y-4 max-w-md">
          <div>
            <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Nombre</label>
            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
          </div>
          <div>
            <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
          </div>
          <button type="submit" class="w-full py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Cambio de contraseña --}}
  <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200">
    <button onclick="document.getElementById('passwordSection').classList.toggle('hidden')" class="w-full flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">lock</span>
        </div>
        <div class="text-left">
          <p class="font-medium">Cambiar Contraseña</p>
          <p class="text-on-surface-variant text-sm">Actualiza tu contraseña de acceso</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-outline">expand_more</span>
    </button>
    <div id="passwordSection" class="hidden border-t border-outline-variant p-4">
      <form method="POST" action="{{ route('perfil.password') }}">
        @csrf
        @method('PUT')
        <div class="space-y-4 max-w-md">
          <div>
            <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Contraseña Actual</label>
            <input type="password" name="current_password" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
          </div>
          <div>
            <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Nueva Contraseña</label>
            <input type="password" name="password" id="password" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
          </div>
          <div>
            <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
          </div>
          <x-password-checklist password-id="password" confirm-id="password_confirmation" title="La nueva contraseña debe cumplir:"/>
          <button type="submit" class="w-full py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Actualizar Contraseña</button>
        </div>
      </form>
    </div>
  </div>
  {{-- Preguntas de seguridad --}}
  <div class="bg-surface-container-lowest shadow rounded-xl border border-slate-200">
    <button onclick="document.getElementById('securitySection').classList.toggle('hidden')" class="w-full flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">security</span>
        </div>
        <div class="text-left">
          <p class="font-medium">Preguntas de Seguridad</p>
          <p class="text-on-surface-variant text-sm">Usadas para recuperar tu contraseña</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-outline">expand_more</span>
    </button>
    <div id="securitySection" class="hidden border-t border-outline-variant p-4">
      <form method="POST" action="{{ route('perfil.security') }}">
        @csrf
        @method('PUT')
        <div class="space-y-6 max-w-md">
          @foreach(range(1, 3) as $i)
          @php $prevAnswer = $userAnswers->get($i - 1); @endphp
          <div class="space-y-3 p-4 bg-surface-container-low rounded-lg">
            <p class="font-medium text-sm text-on-surface-variant">Pregunta {{ $i }}</p>
            <div>
              <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Selecciona una pregunta</label>
              <select name="question_{{ $i }}" required class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md">
                <option value="">-- Seleccionar --</option>
                @foreach($questions as $q)
                <option value="{{ $q->id }}" {{ $prevAnswer && $prevAnswer->security_question_id == $q->id ? 'selected' : '' }}>{{ $q->question }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Nueva respuesta</label>
              <input type="text" name="answer_{{ $i }}" required placeholder="Escribe tu respuesta" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-body-md"/>
            </div>
          </div>
          @endforeach
          <button type="submit" class="w-full py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:brightness-110 transition-all">Guardar Preguntas de Seguridad</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
