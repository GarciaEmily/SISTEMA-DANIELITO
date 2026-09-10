<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Actividades</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Actividades')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>📚 Lista de Actividades</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Administra las actividades, asignación de niños y registro de asistencias.
                </p>
            </div>

            <div class="buttons" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>

                <a href="{{ route('actividades.create') }}" class="btn btn-naranja">
                    ➕ Nueva Actividad
                </a>
            </div>

        </div>
    </div>

    @if($actividades->count())

        <div class="panel">

            <h2>Actividades registradas</h2>

            <div class="table-container">

                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($actividades as $actividad)
                            <tr>
                                <td><strong>{{ $actividad->nombre }}</strong></td>
                                <td>{{ $actividad->descripcion }}</td>
                                <td>{{ \Carbon\Carbon::parse($actividad->fecha_actividad)->format('d/m/Y') }}</td>
                                <td>
                                    @if($actividad->tipo == 'normal')
                                        <span class="badge badge-normal">Normal</span>
                                    @else
                                        <span class="badge badge-intervencion">Intervención</span>
                                    @endif
                                </td>
                                <td>
                                    @if($actividad->grupos && $actividad->grupos->count())
                                        @foreach($actividad->grupos as $grupo)
                                            <span class="badge badge-normal">{{ $grupo->nombre }}</span>
                                        @endforeach
                                    @else
                                        <span style="color:#999;">Sin grupo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($actividad->activa)
                                        <span class="badge badge-active">Activa</span>
                                    @else
                                        <span class="badge badge-inactive">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acciones" style="display: flex; flex-direction: column; gap: 5px;">
                                        
                                        <!-- Menú Desplegable Dinámico de Reportes -->
                                        <div class="dropdown" style="position: relative; display: inline-block; width: 100%;">
                                            <button type="button" onclick="toggleDropdown(event, 'dropdown-{{ $actividad->id }}')" class="btn-action" style="background: #3498db; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 11px; text-align: center; border: none; cursor: pointer; width: 100%;">
                                                📄 Reportes ▾
                                            </button>
                                            
                                            <div id="dropdown-{{ $actividad->id }}" class="dropdown-content" style="display: none; position: absolute; right: 0; background-color: #ffffff; min-width: 180px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 10; border-radius: 6px; overflow: hidden; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;">
                                                
                                                <!-- Opción Histórico Completo -->
                                                <a href="{{ route('reportes.asistencia_actividad.pdf', ['actividad' => $actividad->id, 'periodo' => 'todo']) }}" target="_blank" style="color: #2c3e50; padding: 8px 12px; text-decoration: none; display: block; font-size: 11px; text-align: left; font-weight: bold; border-bottom: 1px solid #eee;">
                                                    📂 Histórico completo
                                                </a>

                                                <!-- Bucle dinámico para los últimos 12 meses -->
                                                @for ($i = 0; $i < 12; $i++)
                                                    @php
                                                        $fechaMes = \Carbon\Carbon::now()->subMonths($i);
                                                        $periodoParam = $fechaMes->format('Y-m'); // Ej: 2026-08
                                                        $nombreMes = ucfirst($fechaMes->translatedFormat('F Y')); // Ej: Agosto 2026
                                                    @endphp

                                                    <a href="{{ route('reportes.asistencia_actividad.pdf', ['actividad' => $actividad->id, 'periodo' => $periodoParam]) }}" target="_blank" style="color: #333; padding: 8px 12px; text-decoration: none; display: block; font-size: 11px; text-align: left; border-top: 1px solid #f1f1f1;">
                                                        📅 {{ $nombreMes }}
                                                    </a>
                                                @endfor

                                            </div>
                                        </div>

                                        <a href="{{ route('actividades.asignar_ninos', $actividad->id) }}" class="btn-action btn-assign">
                                            👦 Asignar
                                        </a>

                                        <a href="{{ route('asistencias.create', $actividad->id) }}" class="btn-action btn-attendance">
                                            ✅ Asistencia
                                        </a>

                                        <a href="{{ route('asistencias.index', $actividad->id) }}" class="btn-action btn-view">
                                            📋 Ver
                                        </a>

                                        <a href="{{ route('actividades.edit', $actividad->id) }}" class="btn-action btn-edit">
                                            ✏️ Editar
                                        </a>

                                        <form action="{{ route('actividades.destroy', $actividad->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta actividad?')">
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
            <p>📚 No hay actividades registradas.</p>
        </div>
    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

<!-- Estilos Hover para las opciones del dropdown -->
<style>
    .dropdown-content a:hover {
        background-color: #f8f9fa;
        color: #2980b9 !important;
    }
</style>

<!-- Script JavaScript para controlar la apertura y cierre de los menús -->
<script>
    function toggleDropdown(event, dropdownId) {
        event.stopPropagation();
        
        document.querySelectorAll('.dropdown-content').forEach(function(content) {
            if (content.id !== dropdownId) {
                content.style.display = 'none';
            }
        });

        var dropdown = document.getElementById(dropdownId);
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    }

    window.onclick = function(event) {
        if (!event.target.matches('.btn-action')) {
            document.querySelectorAll('.dropdown-content').forEach(function(content) {
                content.style.display = 'none';
            });
        }
    }
</script>

</body>
</html>