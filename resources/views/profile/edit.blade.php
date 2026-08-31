<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil – Fundación Danielito</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
    <style>
        .perfil-wrapper {
            max-width: 560px;
            margin: 40px auto;
            padding: 0 20px 60px;
        }

        .perfil-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(29, 74, 75, 0.10);
            overflow: hidden;
        }

        .perfil-header {
            background: linear-gradient(135deg, #1d4a4b, #31706e);
            padding: 28px 32px;
            color: #ffffff;
        }

        .perfil-header h3 {
            margin: 0 0 4px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 22px;
        }

        .perfil-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 14px;
        }

        .perfil-body {
            padding: 28px 32px 32px;
        }

        .perfil-alert-success {
            background: #e7f6f3;
            border: 1px solid #31706e;
            color: #1d4a4b;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .perfil-group {
            margin-bottom: 18px;
        }

        .perfil-group label {
            display: block;
            font-weight: 600;
            color: #1d4a4b;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .perfil-group input {
            width: 100%;
            padding: 11px 16px;
            border: 1px solid #d8e2e1;
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.15s ease;
        }

        .perfil-group input:focus {
            outline: none;
            border-color: #ea8028;
        }

        .perfil-error {
            color: #c0392b;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .perfil-divider {
            border: none;
            border-top: 1px solid #eceff0;
            margin: 28px 0 18px;
        }

        .perfil-sub {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #31706e;
            font-size: 15px;
            margin: 0 0 4px;
        }

        .perfil-hint {
            color: #8a9694;
            font-size: 12.5px;
            margin: 0 0 16px;
        }

        .perfil-submit {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #ea8028, #d96e18);
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            margin-top: 8px;
            transition: opacity 0.15s ease;
        }

        .perfil-submit:hover {
            opacity: 0.92;
        }
    </style>
</head>

<body style="background:#f4f7f6; margin:0;">

@include('partials.topbar')

<div class="perfil-wrapper">
    <div style="margin-bottom: 15px;">
        <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; color: #31706e; text-decoration: none; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 14px; transition: opacity 0.15s ease;">
            <span style="margin-right: 6px; font-size: 16px;">←</span> Volver al Dashboard
        </a>
    </div>

<div class="perfil-wrapper">
    <div class="perfil-card">
        <div class="perfil-header">
            <h3>Mi Perfil</h3>
            <p>Gestiona tu información de acceso y datos personales.</p>
        </div>

        <div class="perfil-body">

            @if(session('success'))
                <div class="perfil-alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="perfil-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}">
                    @error('nombre')
                        <small class="perfil-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="perfil-group">
                    <label>Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $user->apellido) }}">
                    @error('apellido')
                        <small class="perfil-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="perfil-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <small class="perfil-error">{{ $message }}</small>
                    @enderror
                </div>

                <hr class="perfil-divider">

                <p class="perfil-sub">Cambiar contraseña</p>
                <p class="perfil-hint">Deja estos campos en blanco si no quieres cambiar tu contraseña.</p>

                <div class="perfil-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••">
                    @error('password')
                        <small class="perfil-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="perfil-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••">
                </div>

                <button type="submit" class="perfil-submit">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>