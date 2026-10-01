<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Niños Eliminados</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Niños eliminados')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>🗑 Niños eliminados</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Niños eliminados de la lista principal. Se pueden restaurar en cualquier momento sin perder su historial.
                </p>
            </div>

            <div class="buttons">
                <a href="{{ route('ninos.index') }}" class="btn btn-secundario">
                    ← Volver a niños
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alerts">
            <p>✅ {{ session('success') }}</p>
        </div>
    @endif

    @if($ninosEliminados->count())

        <div class="panel">

            <h2>Niños eliminados registrados</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Maestro</th>
                            <th>Eliminado el</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ninosEliminados as $nino)
                            <tr>
                                <td>{{ $nino->codigo }}</td>
                                <td><strong>{{ $nino->nombres }} {{ $nino->apellidos }}</strong></td>
                                <td>{{ $nino->grupo->nombre ?? 'Sin grupo' }}</td>
                                <td>
                                    {{ $nino->maestro->nombre ?? '' }}
                                    {{ $nino->maestro->apellido ?? '' }}
                                </td>
                                <td>{{ $nino->deleted_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('ninos.restore', $nino->id) }}" method="POST" onsubmit="return confirm('¿Restaurar a {{ $nino->nombres }} {{ $nino->apellidos }}? Volverá a aparecer en todas las listas y selectores.')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-edit">
                                            ♻️ Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @else
        <div class="alerts">
            <p>⚠️ No hay niños eliminados.</p>
        </div>
    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>
