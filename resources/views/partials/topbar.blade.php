@php
    use Carbon\Carbon;

    Carbon::setLocale('es');

    $fechaActual = Carbon::now()->translatedFormat('l d \d\e F \d\e Y');

    $nombreUsuario = auth()->user()->nombre ?? '';
    $apellidoUsuario = auth()->user()->apellido ?? '';

    $iniciales = strtoupper(
        substr($nombreUsuario, 0, 1) .
        substr($apellidoUsuario, 0, 1)
    );
@endphp

<div class="topbar">
    <div class="topbar-brand">
        {{-- Envolvemos el logo y el texto en un enlace que lleva al dashboard --}}
        <a href="{{ route('dashboard') }}" class="brand-link" style="text-decoration: none; display: flex; align-items: center; color: inherit;">
            <img
                src="{{ asset('img/logo-danielito.png') }}"
                alt="Logo Fundación Danielito"
                class="topbar-logo"
            >

            <div>
                <h1>Fundación <span>Danielito</span></h1>
                <p>@yield('panel-title', 'Sistema Fundación Danielito')</p>
            </div>
        </a>
    </div>

    <div class="user-info">
        <div class="user-avatar">
            {{ $iniciales }}
        </div>

        <div class="user-text">
            <strong>
                <a href="{{ route('profile.edit') }}" class="profile-link">
                    {{ $nombreUsuario }} {{ $apellidoUsuario }}
                </a>
            </strong>

            <span>{{ $fechaActual }}</span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                Cerrar sesión
            </button>
        </form>
    </div>
</div>

<div class="wave-divider"></div>