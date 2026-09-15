<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Grupo</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>
@section('panel-title', 'Editar Grupo')

@include('partials.topbar')

<div class="container-form">

    <a href="{{ route('grupos.index') }}" class="btn-volver">
        ← Volver a grupos
    </a>

    <h1 class="form-title">
        👥 Editar Grupo
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

    <form method="POST" action="{{ route('grupos.update', $grupo->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre del grupo</label>
                <input type="text" name="nombre" value="{{ old('nombre', $grupo->nombre) }}" required>
            </div>

            <div class="form-group">
                <label>Maestro asignado</label>
                <select name="maestro_id">
                    <option value="">Sin maestro asignado</option>
                    @foreach($maestros as $maestro)
                        <option value="{{ $maestro->id }}" @selected(old('maestro_id', $grupo->maestro_id) == $maestro->id)>
                            {{ $maestro->nombre }} {{ $maestro->apellido }}
                        </option>
                    @endforeach
                </select>
                <small style="color:#888; display:block; margin-top:4px;">
                    Solo se listan usuarios con rol Maestro. Un grupo sin maestro no podrá recibir niños nuevos hasta que se le asigne uno.
                </small>
            </div>

            <div class="form-group full-width">
                <label>Descripción</label>
                <textarea name="descripcion" placeholder="Describe el grupo...">{{ old('descripcion', $grupo->descripcion) }}</textarea>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $grupo->activo))>
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
