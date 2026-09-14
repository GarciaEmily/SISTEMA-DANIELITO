<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Actividad</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/danielito.css') }}">


</head>

<body>
@section('panel-title', 'Editar Actividad')

@include('partials.topbar')

<div class="container-form">

    <a href="{{ route('dashboard') }}"
       class="btn-volver">
        ← Volver al panel
    </a>



<h1 class="form-title">
    📚 Editar Actividad
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

        <form method="POST"
              action="{{ route('actividades.update', $actividad->id) }}">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nombre de la actividad</label>

                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $actividad->nombre) }}"
                       placeholder="Ej: Taller de liderazgo"
                       required>

            </div>

            <div class="form-group">

                <label>Descripción</label>

                <textarea name="descripcion"
                          placeholder="Describe la actividad...">{{ old('descripcion', $actividad->descripcion) }}</textarea>

            </div>

            <div class="form-group" id="grupo-fecha">

                <label>Fecha de actividad</label>

                <input type="date"
                       name="fecha_actividad"
                       id="fecha_actividad"
                       value="{{ old('fecha_actividad', $actividad->fecha_actividad ? \Carbon\Carbon::parse($actividad->fecha_actividad)->format('Y-m-d') : '') }}">

                <small style="color:#888; display:block; margin-top:4px;">
                    Solo aplica para actividades de tipo "Normal" (evento de un solo día).
                </small>

            </div>

            <div class="form-group">

                <label>Tipo de actividad</label>

                <select name="tipo" id="tipo" required>

                    <option value="normal" @selected(old('tipo', $actividad->tipo) === 'normal')>
                        Normal
                    </option>

                    <option value="intervencion" @selected(old('tipo', $actividad->tipo) === 'intervencion')>
                        Intervención
                    </option>

                </select>

                <small style="color:#888; display:block; margin-top:4px;">
                    Una "Intervención" es un programa recurrente (ej: "Tú vales, yo cuesto"). No requiere fecha única; la asistencia se registrará por sesión.
                </small>

            </div>

            <div class="form-group">

                <label>Grupos participantes</label>

                <div class="group-container">

                    @php
                        $gruposSeleccionados = $actividad->grupos->pluck('id')->toArray();
                    @endphp

                    @foreach($grupos as $grupo)

                        <div class="group-card">

                            <label>

                                <input type="checkbox"
                                       name="grupos[]"
                                       value="{{ $grupo->id }}"
                                       @checked(in_array($grupo->id, old('grupos', $gruposSeleccionados)))>

                                {{ $grupo->nombre }}

                            </label>

                        </div>

                    @endforeach

                </div>

            </div>

            <button type="submit"
                    class="btn-guardar">

                💾 Guardar cambios

            </button>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect = document.getElementById('tipo');
        const grupoFecha = document.getElementById('grupo-fecha');
        const fechaInput = document.getElementById('fecha_actividad');

        function toggleFecha() {
            if (tipoSelect.value === 'intervencion') {
                grupoFecha.style.display = 'none';
                fechaInput.value = '';
            } else {
                grupoFecha.style.display = '';
            }
        }

        tipoSelect.addEventListener('change', toggleFecha);
        toggleFecha();
    });
</script>

</body>
</html>