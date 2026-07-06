@props([
    'passwordId' => 'password',
    'confirmId' => 'password_confirmation',
    'title' => 'La contraseña debe cumplir:',
])

{{-- Componente de validación de contraseña en vivo: verifica longitud, mayúsculas, minúsculas, número, símbolo y coincidencia --}}

<div
    id="password-checklist"
    class="space-y-1.5 mt-3 p-3 bg-surface-container-low rounded-lg border border-outline-variant text-sm"
    data-password-id="{{ $passwordId }}"
    data-confirm-id="{{ $confirmId }}"
>
    <p class="font-label-md text-label-md text-on-surface-variant font-semibold mb-2">{{ $title }}</p>

    <div class="flex items-center gap-2 text-error" data-rule="length">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Al menos <strong>8 caracteres</strong></span>
    </div>

    <div class="flex items-center gap-2 text-error" data-rule="uppercase">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Al menos una letra <strong>mayúscula</strong> (A-Z)</span>
    </div>

    <div class="flex items-center gap-2 text-error" data-rule="lowercase">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Al menos una letra <strong>minúscula</strong> (a-z)</span>
    </div>

    <div class="flex items-center gap-2 text-error" data-rule="number">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Al menos un <strong>número</strong> (0-9)</span>
    </div>

    <div class="flex items-center gap-2 text-error" data-rule="symbol">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Al menos un <strong>símbolo</strong> (@, #, $, %, !, etc.)</span>
    </div>

    <div class="flex items-center gap-2 text-error" data-rule="match">
        <span class="material-symbols-outlined text-[18px]">cancel</span>
        <span>Las contraseñas <strong>coinciden</strong></span>
    </div>
</div>

<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    var checklist = document.getElementById('password-checklist');
    if (!checklist) return;

    var password = document.getElementById(checklist.dataset.passwordId);
    var confirmPw = document.getElementById(checklist.dataset.confirmId);
    if (!password) return;

    var OK = 'text-tertiary';
    var ERR = 'text-error';

    function update() {
        var val = password.value;
        var cval = confirmPw ? confirmPw.value : '';

        var checks = {
            length: val.length >= 8,
            uppercase: /[A-Z]/.test(val),
            lowercase: /[a-z]/.test(val),
            number: /[0-9]/.test(val),
            symbol: /[^a-zA-Z0-9]/.test(val),
            match: val.length > 0 && val === cval,
        };

        checklist.querySelectorAll('[data-rule]').forEach(function (el) {
            var rule = el.dataset.rule;
            var met = checks[rule];
            var icon = el.querySelector('.material-symbols-outlined');
            var cls = met ? OK : ERR;
            el.className = 'flex items-center gap-2 ' + cls;
            if (icon) {
                icon.textContent = met ? 'check_circle' : 'cancel';
                icon.className = 'material-symbols-outlined text-[18px]';
            }
        });
    }

    password.addEventListener('input', update);
    if (confirmPw) confirmPw.addEventListener('input', update);
});
</script>
