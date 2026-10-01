<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visitas domiciliarias</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Visitas domiciliarias')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>🏠 Visitas domiciliarias</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    @if($ninoFiltro)
                        Visitas registradas a {{ $ninoFiltro->nombres }} {{ $ninoFiltro->apellidos }}.
                    @else
                        Historial de visitas realizadas a los niños.
                    @endif
                </p>
            </div>

            <div class="buttons" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>

                <a href="{{ route('visitas.create', $ninoFiltro ? ['nino_id' => $ninoFiltro->id] : []) }}" class="btn btn-naranja">
                    ➕ Registrar visita
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alerts">
            <p>✅ {{ session('success') }}</p>
        </div>
    @endif

    @if($visitas->count())

        <div class="panel">

            <h2>Visitas registradas</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Niño</th>
                            <th>Grupo</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Realizado por</th>
                            <th>Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitas as $visita)
                            <tr>
                                <td><strong>{{ $visita->nino->nombres ?? '' }} {{ $visita->nino->apellidos ?? '' }}</strong></td>
                                <td>{{ $visita->nino->grupo->nombre ?? 'Sin grupo' }}</td>
                                <td>{{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') }}</td>
                                <td>{{ $visita->motivo }}</td>
                                <td>{{ $visita->realizadoPor->nombre ?? '' }} {{ $visita->realizadoPor->apellido ?? '' }}</td>
                                <td>
                                    @if($visita->foto_path)
                                        <a href="{{ Storage::url($visita->foto_path) }}" target="_blank">
                                            <img src="{{ Storage::url($visita->foto_path) }}" alt="Foto de la visita" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                        </a>
                                    @else
                                        <span style="color:#999;">Sin foto</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acciones">
                                        <a href="{{ route('visitas.edit', $visita->id) }}" class="btn-action btn-edit">
                                            ✏️ Editar
                                        </a>

                                        @if(auth()->user()->role->nombre === 'Directora' || auth()->user()->role->nombre === 'Administrador')
                                            <form action="{{ route('visitas.destroy', $visita->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta visita?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete">
                                                    🗑 Eliminar
                                                </button>
                                            </form>
                                        @endif
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
            <p>🏠 No hay visitas registradas.</p>
        </div>
    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>
