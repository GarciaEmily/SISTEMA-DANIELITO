<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Grupos</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Grupos')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>👥 Lista de Grupos</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Administra los grupos de niños y el maestro asignado a cada uno.
                </p>
            </div>

            <div class="buttons" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>

                <a href="{{ route('grupos.create') }}" class="btn btn-naranja">
                    ➕ Crear grupo
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alerts">
            <p>✅ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="error-box">
            <p class="error-text">❌ {{ session('error') }}</p>
        </div>
    @endif

    @if($grupos->count())

        <div class="panel">

            <h2>Grupos registrados</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Maestro asignado</th>
                            <th>Niños</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grupos as $grupo)
                            <tr>
                                <td><strong>{{ $grupo->nombre }}</strong></td>
                                <td>
                                    @if($grupo->maestro)
                                        {{ $grupo->maestro->nombre }} {{ $grupo->maestro->apellido }}
                                    @else
                                        <span style="color:#999;">Sin maestro asignado</span>
                                    @endif
                                </td>
                                <td>{{ $grupo->ninos_count }}</td>
                                <td>
                                    @if($grupo->activo)
                                        <span class="badge badge-active">Activo</span>
                                    @else
                                        <span class="badge badge-inactive">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acciones" style="display: flex; flex-direction: column; gap: 5px;">
                                        <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn-action btn-edit">
                                            ✏️ Editar
                                        </a>

                                        <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar el grupo {{ $grupo->nombre }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                                                🗑 Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @else
        <div class="alerts">
            <p>⚠️ No hay grupos registrados.</p>
        </div>
    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>
