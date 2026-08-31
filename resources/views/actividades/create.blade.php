<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Actividad</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/danielito.css') }}">


</head>

<body>
@section('panel-title', 'Registrar Actividad')

@include('partials.topbar')

<div class="container-form">

    <a href="{{ route('dashboard') }}"
       class="btn-volver">
        ← Volver al panel
    </a>



<h1 class="form-title">
    📚 Registrar Actividad
</h1>
        <form method="POST"
              action="{{ route('actividades.store') }}">

            @csrf

            <div class="form-group">

                <label>Nombre de la actividad</label>

                <input type="text"
                       name="nombre"
                       placeholder="Ej: Taller de liderazgo"
                       required>

            </div>

            <div class="form-group">

                <label>Descripción</label>

                <textarea name="descripcion"
                          placeholder="Describe la actividad..."></textarea>

            </div>

            <div class="form-group" id="grupo-fecha">

                <label>Fecha de actividad</label>

                <input type="date"
                       name="fecha_actividad"
                       id="fecha_actividad">

                <small style="color:#888; display:block; margin-top:4px;">
                    Solo aplica para actividades de tipo "Normal" (evento de un solo día).
                </small>

            </div>

            <div class="form-group">

                <label>Tipo de actividad</label>

                <select name="tipo" id="tipo" required>

                    <option value="normal">
                        Normal
                    </option>

                    <option value="intervencion">
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

                    @foreach($grupos as $grupo)

                        <div class="group-card">

                            <label>

                                <input type="checkbox"
                                       name="grupos[]"
                                       value="{{ $grupo->id }}">

                                {{ $grupo->nombre }}

                            </label>

                        </div>

                    @endforeach

                </div>

            </div>

            <button type="submit"
                    class="btn-guardar">

                💾 Guardar actividad

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
                fechaInput.value = ''; // limpiamos por si ya la habían llenado
            } else {
                grupoFecha.style.display = '';
            }
        }

        tipoSelect.addEventListener('change', toggleFecha);
        toggleFecha(); // aplicar al cargar la página, por si el navegador recuerda la selección
    });
</script>

</body>
</html>