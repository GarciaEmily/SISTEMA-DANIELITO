<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>
@section('panel-title', 'Crear Usuario')

@include('partials.topbar')

<div class="container-form">

    <a href="{{ route('usuarios.index') }}" class="btn-volver">
        ← Volver a usuarios
    </a>

    <h1 class="form-title">
        👤 Crear Usuario
    </h1>

    @if($errors->any())
        <div class="error-box">
            <p class="error-text">
                ❌ Revisa los siguientes errores:
            </p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="role_id" required>
                    <option value="">Seleccione</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" @selected(old('role_id') == $rol->id)>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required minlength="8">
            </div>

            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required minlength="8">
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="activo" value="1" checked>
                <label>Activo</label>
            </div>

        </div>

        <button type="submit" class="btn-guardar">
            💾 Guardar usuario
        </button>

    </form>

</div>

</body>
</html>
