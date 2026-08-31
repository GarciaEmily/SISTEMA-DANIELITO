<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center mb-3">
            <img src="{{ asset('img/logo-danielito.png') }}" alt="Logo Fundación Danielito" class="h-20 w-auto object-contain">
        </div>
        <h2 class="text-2xl font-bold text-green-950 tracking-tight">Fundación Danielito</h2>
        <p class="text-sm text-green-900 mt-1">Gestión Interna y Soporte Integral</p>
    </div>

    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="font-semibold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm placeholder:text-slate-300" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="ejemplo@danielito.org" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="font-semibold text-slate-700" />
            <x-text-input id="password" class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm placeholder:text-slate-300"
                type="password"
                name="password"
                required 
                autocomplete="current-password" 
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-slate-500 selection:bg-transparent">{{ __('Acuérdate de mí') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-blue-600 hover:text-blue-700 font-medium hover:underline focus:outline-none" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                {{ __('ACCESO') }}
            </button>
        </div>
    </form>
</x-guest-layout>