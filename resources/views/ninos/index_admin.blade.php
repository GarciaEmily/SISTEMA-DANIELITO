<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Niños</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Administración de Niños')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>👦 Niños agrupados por grupo</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Administración general de niños registrados en la fundación.
                </p>
            </div>

            <div class="buttons">
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>

                <a href="{{ route('ninos.create') }}" class="btn btn-naranja">
                    ➕ Registrar Niño
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alerts">
            <p>✅ {{ session('success') }}</p>
        </div>
    @endif

    @forelse($ninosAgrupados as $grupo => $ninos)

        <details class="group-card" open>

            {{-- Cabecera del Grupo Adaptada con el Selector de Meses --}}
            <summary class="group-summary" style="display: flex; justify-content: space-between; align-items: center; width: 100%; box-sizing: border-box;">
                
                {{-- Bloque Izquierdo: Datos de Grupo --}}
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>{{ $grupo }}</span>
                    <span class="badge badge-info">
                        {{ $ninos->count() }} niño(s)
                    </span>
                </div>

                {{-- Bloque Derecho: Formulario Selector de Mes + Botón PDF --}}
                @if($ninos->count() > 0)
                    <div class="prevent-collapse-zone" style="margin-right: 15px;">
                        <form action="{{ route('reportes.asistencia.grupo', $ninos->first()->grupo_id) }}" 
                              method="GET" 
                              target="_blank" 
                              style="display: flex; align-items: center; gap: 8px; margin: 0;">
                            
                            <select name="mes" class="btn-prevent-collapse" style="padding: 5px 10px; font-size: 12px; font-weight: 500; border: 1px solid var(--text-mid); border-radius: 6px; background-color: white; color: var(--text-dark); cursor: pointer; font-family: 'Montserrat', sans-serif;">
                                <option value="">Mes Actual</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>

                            <button type="submit" class="btn btn-secundario btn-prevent-collapse" style="padding: 5px 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: none;">
                                📄 Exportar Asistencia (PDF)
                            </button>
                        </form>
                    </div>
                @endif

            </summary>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Maestro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ninos as $nino)
                            <tr>
                                <td>{{ $nino->codigo }}</td>
                                <td>{{ $nino->nombres }}</td>
                                <td>{{ $nino->apellidos }}</td>
                                <td>
                                    {{ $nino->maestro->nombre ?? '' }}
                                    {{ $nino->maestro->apellido ?? '' }}
                                </td>
                                <td>
                                    <div class="acciones">
                                        <a href="{{ route('ninos.show', $nino->id) }}" class="btn-action btn-view">Ver</a>
                                        <a href="{{ route('ninos.edit', $nino->id) }}" class="btn-action btn-edit">Editar</a>
                                        <form action="{{ route('ninos.destroy', $nino->id) }}" method="POST" class="form-eliminar" onsubmit="return confirm('¿Seguro que deseas eliminar este niño?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </details>

    @empty
        <div class="alerts">
            <p>⚠️ No hay niños registrados.</p>
        </div>
    @endforelse

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

{{-- SCRIPT INTERADO CORREGIDO Y AMPLIADO --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Restaurar posición de scroll
        const savedScrollPosition = sessionStorage.getItem("ninos-list-scroll");
        if (savedScrollPosition) {
            window.scrollTo(0, parseInt(savedScrollPosition));
            sessionStorage.removeItem("ninos-list-scroll"); 
        }

        // 2. Guardar scroll en clics de navegación habituales
        document.querySelectorAll('.btn-action, .btn').forEach(button => {
            button.addEventListener('click', function () {
                sessionStorage.setItem("ninos-list-scroll", window.scrollY);
            });
        });

        // 3. Guardar scroll en envíos de formularios comunes (como Eliminar)
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function () {
                sessionStorage.setItem("ninos-list-scroll", window.scrollY);
            });
        });

        // 🔥 EVITAR COLAUPSO/APERTURA DEL ACORDEÓN:
        // Detiene el evento click en selectores y botones del reporte para que no afecte al <summary>
        document.querySelectorAll('.btn-prevent-collapse').forEach(element => {
            element.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });
    });
</script>

</body>
</html>