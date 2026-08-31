<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Maestro – Fundación Danielito</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@include('partials.topbar')

<!-- CONTENIDO -->
<div class="container">

    <p class="section-title">Resumen general</p>

    <div class="cards">

        <div class="card card-ninos">
            <div class="card-icon">👦</div>
            <h3>Mis niños</h3>
            <div class="card-number">{{ $totalMisNinos }}</div>
        </div>

        <div class="card card-activ">
            <div class="card-icon">📚</div>
            <h3>Mis actividades</h3>
            <div class="card-number">{{ $totalMisActividades }}</div>
        </div>

        <div class="card card-asist">
            <div class="card-icon">✅</div>
            <h3>Asistencias</h3>
            <div class="card-number">{{ $totalMisAsistencias }}</div>
        </div>
        <div class="card card-asist">
    <div class="card-icon">📊</div>
    <h3>Asistencia del mes</h3>
    <div class="card-number">{{ $asistenciaMes ?? 0 }}%%</div>
</div>

<div class="content-grid">

    <div class="panel">
        <h2>🎂 Cumpleaños cercanos</h2>

        <table>
            <thead>
                <tr>
                    <th>Niño</th>
                    <th>Fecha nacimiento</th>
                    <th>Edad</th>
                </tr>
            </thead>

            <tbody>
                @forelse($cumpleanosCercanos as $nino)
                    <tr>
                        <td>{{ $nino->nombres }} {{ $nino->apellidos }}</td>
                        <td>{{ $nino->fecha_nacimiento }}</td>
                        <td>{{ $nino->edad }} años</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No hay cumpleaños cercanos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2>🏆 Niños con más asistencias</h2>

        <table>
            <thead>
                <tr>
                    <th>Niño</th>
                    <th>Total presentes</th>
                </tr>
            </thead>

            <tbody>
                @forelse($misMasAsistentes as $item)
                    <tr>
                        <td>
                            {{ $item->nino->nombres ?? '' }}
                            {{ $item->nino->apellidos ?? '' }}
                        </td>
                        <td>{{ $item->total_asistencias }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay asistencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="panel asistencia-mes">
    <h2>📅 Asistencia del mes</h2>

    <div class="cards">

        <div class="card">
            <h3>Total</h3>
            <p>{{ $miAsistenciaMes->total ?? 0 }}</p>
        </div>

        <div class="card">
            <h3>✅ Presentes</h3>
            <p>{{ $miAsistenciaMes->presentes ?? 0 }}</p>
        </div>

        <div class="card">
            <h3>❌ Ausentes</h3>
            <p>{{ $miAsistenciaMes->ausentes ?? 0 }}</p>
        </div>

        <div class="card">
            <h3>⚠️ Justificados</h3>
            <p>{{ $miAsistenciaMes->justificados ?? 0 }}</p>
        </div>

    </div>
</div>

    <div class="quick-actions">
        <h2>Accesos rápidos</h2>
        <div class="buttons">
            <a href="{{ route('ninos.index') }}" class="btn btn-ninos">
                👦 Ver niños asignados
            </a>
            <a href="{{ route('actividades.index') }}" class="btn btn-actividades">
                📘 Ver mis actividades
            </a>
        </div>
    </div>

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>