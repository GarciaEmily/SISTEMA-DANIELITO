<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Niños</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Asignar Niños')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>👦 Asignar niños a actividad</h2>

                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Selecciona los niños que participarán en esta actividad.
                </p>
            </div>

            <a href="{{ route('actividades.index') }}" class="btn btn-secundario">
                ← Volver a actividades
            </a>

        </div>
    </div>

    <div class="panel" style="margin-bottom:25px;">

        <h2>{{ $actividad->nombre }}</h2>

        <p style="color:var(--text-mid); font-weight:600;">
            <strong>Grupos participantes:</strong>

            @foreach($actividad->grupos as $grupo)
                <span class="badge badge-info">
                    {{ $grupo->nombre }}
                </span>
            @endforeach
        </p>

    </div>

    <form method="POST" action="{{ route('actividades.guardar_ninos', $actividad->id) }}">
        @csrf

        @forelse($ninosAgrupados as $grupo => $ninos)

            <details class="group-card" open>

                <summary class="group-summary">
                    <span>{{ $grupo }}</span>

                    <span class="badge badge-info">
                        {{ $ninos->count() }} niño(s)
                    </span>
                </summary>

                <div class="children-container">

                    @foreach($ninos as $nino)

                        <label class="child-card">

                            <input type="checkbox"
                                   name="ninos[]"
                                   value="{{ $nino->id }}"
                                   {{ in_array($nino->id, $ninosAsignados) ? 'checked' : '' }}>

                            <div class="child-info">
                                <span class="child-name">
                                    {{ $nino->nombres }} {{ $nino->apellidos }}
                                </span>

                                <span class="child-code">
                                    Código: {{ $nino->codigo }}
                                </span>
                            </div>

                        </label>

                    @endforeach

                </div>

            </details>

        @empty

            <div class="alerts">
                <p>⚠️ No hay niños registrados en los grupos seleccionados.</p>
            </div>

        @endforelse

        <div class="submit-container">
            <button type="submit" class="btn-guardar">
                💾 Guardar asignación
            </button>
        </div>

    </form>

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>