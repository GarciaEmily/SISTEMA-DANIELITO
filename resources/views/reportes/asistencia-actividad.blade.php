<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencia - {{ $actividad->nombre }}</title>
    <style>
        @page { margin: 25px; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #27ae60; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 18px; margin: 0; color: #27ae60; }
        .header p { margin: 2px 0; font-size: 11px; color: #666; }
        .resumen {
            background: #f4f9f4; border: 1px solid #27ae60; border-radius: 4px;
            padding: 8px 12px; margin-bottom: 15px; display: table; width: 100%;
        }
        .resumen .item { display: table-cell; text-align: center; }
        .resumen .item strong { display: block; font-size: 16px; color: #27ae60; }
        .sesion-titulo {
            background: #eaf6ec; padding: 5px 10px; font-weight: bold;
            margin-top: 15px; border-left: 4px solid #27ae60;
        }
        table.asistencias { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.asistencias th { background: #27ae60; color: white; padding: 5px 8px; text-align: left; font-size: 11px; }
        table.asistencias td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; color: white; }
        .badge-presente { background: #27ae60; }
        .badge-ausente { background: #e74c3c; }
        .badge-tarde { background: #f39c12; }
        .footer { margin-top: 25px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Asistencia por Intervención</h1>
        <p>Fundación Danielito — Warnes, Santa Cruz</p>
    </div>

    <p><strong>Intervención:</strong> {{ $actividad->nombre }}</p>
    @if($actividad->descripcion)
        <p><strong>Descripción:</strong> {{ $actividad->descripcion }}</p>
    @endif
    <p><strong>Período:</strong> {{ $mesNombre }}{{ $anio ? ' ' . $anio : '' }}</p>

    <div class="resumen">
        <div class="item"><strong>{{ $sesiones->count() }}</strong>Sesiones</div>
        <div class="item"><strong>{{ $totalRegistros }}</strong>Registros totales</div>
        <div class="item"><strong>{{ $totalPresentes }}</strong>Presentes</div>
        <div class="item"><strong>{{ $porcentaje }}%</strong>Asistencia</div>
    </div>

    @if($sesiones->count())
        @foreach($sesiones as $fecha => $registros)
            <div class="sesion-titulo">
                Sesión: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                ({{ $registros->count() }} niños)
            </div>
            <table class="asistencias">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del niño</th>
                        <th>Grupo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $index => $asistencia)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $asistencia->nino->apellidos }} {{ $asistencia->nino->nombres }}</td>
                            <td>{{ $asistencia->nino->grupo->nombre ?? 'Sin grupo' }}</td>
                            <td>
                                @if($asistencia->estado == 'presente')
                                    <span class="badge badge-presente">Presente</span>
                                @elseif($asistencia->estado == 'tarde')
                                    <span class="badge badge-tarde">Tarde</span>
                                @else
                                    <span class="badge badge-ausente">Ausente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        <p>No hay sesiones de asistencia registradas para este período.</p>
    @endif

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — © Fundación Danielito
    </div>

</body>
</html>