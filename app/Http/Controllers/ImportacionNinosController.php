<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\NinosImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ImportacionNinosController extends Controller
{
    public function index()
    {
        return view('ninos.importar');
    }

    public function descargarPlantilla()
    {
        // Temporal: para probar que la vista ya carga sin errores
        return response()->json(['message' => 'Aquí se descargará la plantilla Excel próximamente.']);
    }

    // Método sincronizado con tu archivo de rutas: route('ninos.importar.store')
    public function store(Request $request)
    {
        // Validación flexible y robusta para evitar falsos negativos con el tipo MIME
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,zip|extensions:xlsx,xls'
        ], [
            'archivo.required' => 'Por favor, selecciona un archivo.',
        ]);

        try {
        // SOLUCIÓN AL NO TYPE DETECTED: Obtenemos la ruta real del archivo temporal
        $rutaArchivo = $request->file('archivo')->getRealPath();

        // Le pasamos la ruta directamente a la librería Excel
        Excel::import(new NinosImport, $rutaArchivo);

        return redirect()
            ->route('ninos.index')
            ->with('success', '¡Excelente! Los niños fueron importados correctamente.');
            
        } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al importar el archivo: ' . $e->getMessage());
    }
    }
}