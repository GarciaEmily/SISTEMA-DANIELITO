<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis niños</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Mis Niños Asignados')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">
            <div>
                <h2>👦 Mis niños por grupo</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Listado de niños asignados a tu grupo.
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                ← Volver al panel
            </a>
        </div>
    </div>

    @forelse($ninosAgrupados as $grupo => $ninos)

        <div class="panel" style="margin-bottom:25px; padding:0; overflow:hidden;">

            <div class="group-header">
                <h2>{{ $grupo }}</h2>

                <span class="badge badge-info">
                    {{ $ninos->count() }} niño(s)
                </span>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ninos as $nino)
                            <tr>
                                <td><strong>{{ $nino->codigo }}</strong></td>
                                <td>{{ $nino->nombres }}</td>
                                <td>{{ $nino->apellidos }}</td>
                                <td>
                                    <a href="{{ route('ninos.show', $nino->id) }}" class="btn-small">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @empty

        <div class="alerts">
            <p>⚠️ No tienes niños asignados.</p>
        </div>

    @endforelse

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>