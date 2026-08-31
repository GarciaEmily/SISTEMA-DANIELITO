<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Actividades</title>
    <style>
        /* Estilos básicos para impresión en PDF */
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <h1>Reporte Consolidado de Actividades</h1>

    @forelse($actividades as $actividad)
        <div class="actividad-container">
            <h2>{{ $actividad->nombre }}</h2>
            <p><strong>Fecha:</strong> {{ $actividad->fecha_actividad->format('d/m/Y') }}</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Total Asistencias</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividad->asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->grupo->nombre ?? 'Sin grupo' }}</td>
                            <td>{{ $asistencia->total_asistencias ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No hay asistencias registradas para esta actividad.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="page-break"></div> @empty
        <p>No se encontraron actividades en el periodo seleccionado.</p>
    @endforelse

</body>
</html>