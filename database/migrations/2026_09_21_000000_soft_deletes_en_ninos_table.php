<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Unifica "desactivar" y "eliminar" en un solo concepto: eliminar un
        // niño ya no borra la fila (ni bloquea si tiene historial) ni la deja
        // "inactiva" con un booleano aparte — la marca con deleted_at y el
        // scope global de Eloquent la excluye automáticamente de todas las
        // listas/selectores existentes y futuros, sin depender de que cada
        // query se acuerde de filtrar. asistencias/visitas/actividad_nino
        // siguen con onDelete('cascade'), pero como delete() ya no hace un
        // DELETE real, esa cascada nunca se dispara.
        Schema::table('ninos', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Preserva la intención de los niños que ya estaban marcados
        // activo=false: bajo el concepto unificado, "inactivo" pasa a ser
        // "eliminado" (recuperable desde "Niños eliminados"), no se
        // descarta sin más al borrar la columna.
        DB::table('ninos')
            ->where('activo', false)
            ->update(['deleted_at' => now()]);

        Schema::table('ninos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ninos', function (Blueprint $table) {
            $table->boolean('activo')->default(true);
        });

        // Simetría con el up(): quien haya quedado eliminado vuelve a
        // marcarse activo=false en vez de perder ese estado sin más.
        DB::table('ninos')
            ->whereNotNull('deleted_at')
            ->update(['activo' => false]);

        Schema::table('ninos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
