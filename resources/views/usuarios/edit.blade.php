<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>
@section('panel-title', 'Editar Usuario')

@include('partials.topbar')

<div class="container-form">

    <a href="{{ route('usuarios.index') }}" class="btn-volver">
        ← Volver a usuarios
    </a>

    <h1 class="form-title">
        👤 Editar Usuario
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

    <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="role_id" required>
                    <option value="">Seleccione</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" @selected(old('role_id', $usuario->role_id) == $rol->id)>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" minlength="8">
                <small style="color:#888; display:block; margin-top:4px;">
                    Deja estos campos en blanco para mantener la contraseña actual.
                </small>
            </div>

            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirmation" minlength="8">
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $usuario->activo))>
                <label>Activo</label>
            </div>

        </div>

        <button type="submit" class="btn-guardar">
            💾 Guardar cambios
        </button>

    </form>

</div>

</body>
</html>
