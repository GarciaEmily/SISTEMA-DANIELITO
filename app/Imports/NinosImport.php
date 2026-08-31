<?php

namespace App\Imports;

use App\Models\Nino;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class NinosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Si la fila no tiene nombre o apellido, la ignoramos por completo
        if (empty(trim($row['nombres'] ?? '')) || empty(trim($row['apellidos'] ?? ''))) {
            return null; 
        }

        // 2. Procesar la fecha de nacimiento de forma segura para Excel
        $fechaNacimiento = null;
        if (!empty($row['fecha_nacimiento'])) {
            try {
                if (is_numeric($row['fecha_nacimiento'])) {
                    // Si Excel la lee como número interno, la convertimos correctamente
                    $fechaNacimiento = Carbon::instance(ExcelDate::excelToDateTimeObject($row['fecha_nacimiento']))->format('Y-m-d');
                } else {
                    // Si viene como texto, la parseamos normalmente
                    $fechaNacimiento = Carbon::parse($row['fecha_nacimiento'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Si el formato es totalmente ilegible o inválido, le asignamos la fecha de hoy por defecto
                $fechaNacimiento = now()->format('Y-m-d'); 
            }
        } else {
            // Si la celda está vacía, evitamos el error asignando la fecha de hoy
            $fechaNacimiento = now()->format('Y-m-d'); 
        }

        return new Nino([
            'codigo'           => $row['codigo'] ?? 'S/C',
            'nombres'          => trim($row['nombres']),
            'apellidos'        => trim($row['apellidos']),
            'fecha_nacimiento' => $fechaNacimiento,
            'grupo_id'         => !empty($row['grupo_id']) ? $row['grupo_id'] : 1, 
            'maestro_id'       => !empty($row['maestro_id']) ? $row['maestro_id'] : 1, 
            'vulnerable'       => false,
            'fue_al_encuentro' => false,
            'bautizado'        => false,
            'asiste_iglesia'   => false,
            'activo'           => true,
        ]);
    }
}