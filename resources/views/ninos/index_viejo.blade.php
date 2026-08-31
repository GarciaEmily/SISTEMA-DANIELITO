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

            {{-- Cabecera del Grupo Adaptada con el Botón PDF para el Maestro --}}
            <div class="group-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; box-sizing: border-box;">
                
                {{-- Bloque Izquierdo: Nombre del grupo y contador --}}
                <div style="display: flex; align-items: center; gap: 12px;">
                    <h2 style="margin:0;">{{ $grupo }}</h2>
                    <span class="badge badge-info">
                        {{ $ninos->count() }} niño(s)
                    </span>
                </div>

                {{-- Bloque Derecho: Botón PDF Directo --}}
                @if($ninos->count() > 0)
                    <div>
                        <a href="{{ route('reportes.asistencia.grupo', $ninos->first()->grupo_id) }}" 
                           target="_blank" 
                           class="btn btn-secundario" 
                           style="padding: 5px 12px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; background-color: #ffffff; color: var(--text-dark); border: 1px solid #ddd;">
                            📄 Exportar Asistencia (PDF)
                        </a>
                    </div>
                @endif

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

{{-- SCRIPT INTEGRADO PARA MANTENER LA POSICIÓN DEL SCROLL --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Si existe una posición de scroll guardada, la restauramos de inmediato
        const savedScrollPosition = sessionStorage.getItem("maestro-list-scroll");
        if (savedScrollPosition) {
            window.scrollTo(0, parseInt(savedScrollPosition));
            sessionStorage.removeItem("maestro-list-scroll"); // Limpiamos la memoria
        }

        // 2. Escuchar clics en "Ver detalle" o en el botón de regresar
        document.querySelectorAll('.btn-small, .btn, .btn-secundario').forEach(button => {
            button.addEventListener('click', function () {
                sessionStorage.setItem("maestro-list-scroll", window.scrollY);
            });
        });
    });
</script>

</body>
</html>