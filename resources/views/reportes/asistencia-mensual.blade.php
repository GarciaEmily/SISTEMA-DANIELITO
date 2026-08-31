<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia - Fundación Danielito</title>
    <style>
        @page {
            size: letter landscape;
            margin: 8mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 10px;
            line-height: 1.2;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .header-logo {
            font-size: 16px;
            font-weight: bold;
            color: #e67e22; 
        }
        .header-title {
            text-align: right;
            font-size: 13px;
            color: #2c3e50;
            font-weight: bold;
        }
        .meta-info {
            margin-bottom: 12px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 2px 5px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            table-layout: fixed; 
        }
        .report-table th, .report-table td {
            border: 1px solid #bdc3c7;
            padding: 0px;
            text-align: center;
            vertical-align: middle;
            height: 18px;
            line-height: 18px;
        }
        .report-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        .col-nombre {
            text-align: left !important;
            font-weight: bold;
            padding-left: 5px !important;
            width: 180px; 
            white-space: nowrap;
            overflow: hidden;
        }
        .p-mark { color: #27ae60; font-weight: bold; font-size: 10px; } 
        .a-mark { color: #c0392b; font-weight: bold; font-size: 10px; } 
        .j-mark { color: #f39c12; font-weight: bold; font-size: 10px; } 
        
        .col-day {
            width: 25px; 
            font-size: 9px;
        }
        .col-pct {
            background-color: #f1f2f6;
            font-weight: bold;
            width: 32px;
            font-size: 9px;
        }
        .footer {
            position: fixed;
            bottom: -0.3cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 3px;
        }
        .alert-vacio {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-logo" style="vertical-align: middle;">
                @if(file_exists(public_path('img/logo-danielito.png')))
                    <img src="{{ public_path('img/logo-danielito.png') }}" alt="Logo" style="height: 32px; width: auto; vertical-align: middle; margin-right: 8px;">
                @endif
                <span style="vertical-align: middle;">Fundación Danielito</span>
            </td>
            <td class="header-title" style="vertical-align: middle;">REGISTRO MENSUAL DE ASISTENCIA</td>
        </tr>
    </table>

    <div class="meta-info">
        <table>
            <tr>
                <td><strong>Grupo:</strong> {{ $grupo->nombre }}</td>
                {{-- Aquí se mostrará el mes en español perfectamente capitalizado (ej. Agosto / 2026) --}}
                <td><strong>Mes:</strong> {{ ucfirst($mesNombre) }} / {{ $anio }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Maestro Responsable:</strong> 
                    @if($ninos->count() > 0 && $ninos->first()->maestro)
                        {{ $ninos->first()->maestro->nombre ?? '' }} {{ $ninos->first()->maestro->apellido ?? '' }}
                    @else
                        Sin asignar
                    @endif
                </td>
                <td><strong>Generado el:</strong> {{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    @php
        $tieneAsistencias = $ninos->count() > 0 && 
                            isset($matriz[$ninos->first()->id]['registro']) && 
                            $matriz[$ninos->first()->id]['registro']->count() > 0;
    @endphp

    @if(!$tieneAsistencias)
        <div class="alert-vacio">
             No se encontraron registros de asistencia tomados para este grupo en el mes seleccionado.
        </div>
    @endif

    <table class="report-table">
        <thead>
            <tr>
                <th class="col-nombre">Apellidos y Nombres</th>
                @if($tieneAsistencias)
                    @foreach($matriz[$ninos->first()->id]['registro']->keys() as $diaReal)
                        <th class="col-day">{{ $diaReal }}</th>
                    @endforeach
                @else
                    <th class="col-day">-</th>
                @endif
                <th class="col-pct">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ninos as $nino)
                <tr>
                    <td class="col-nombre">{{ $nino->apellidos }} {{ $nino->nombres }}</td>
                    
                    @if($tieneAsistencias)
                        @foreach($matriz[$ninos->first()->id]['registro']->keys() as $i)
                            <td>
                                @if(isset($matriz[$nino->id]['registro'][$i]))
                                    @php 
                                        $estado = $matriz[$nino->id]['registro'][$i]->estado; 
                                    @endphp
                                    
                                    @if($estado == 'presente')
                                        <span class="p-mark">P</span>
                                    @elseif($estado == 'ausente')
                                        <span class="a-mark">A</span>
                                    @elseif($estado == 'justificado')
                                        <span class="j-mark">J</span>
                                    @endif
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        @endforeach
                    @else
                        <td>&nbsp;</td>
                    @endif

                    <td class="col-pct">
                        {{ $tieneAsistencias ? $matriz[$nino->id]['porcentaje'] : '0' }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © Fundación Danielito — Warnes, Santa Cruz | Reporte de control de asistencia interno.
    </div>

</body>
</html>