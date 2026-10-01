<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nullable a nivel de esquema: ya existe una visita real creada antes
        // de este campo, sin foto. La obligatoriedad de la foto para visitas
        // nuevas se aplica en la validacion de VisitaController::store(), no
        // aqui, para no romper ese registro historico.
        Schema::table('visitas', function (Blueprint $table) {
            $table->string('foto_path')->nullable()->after('seguimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropColumn('foto_path');
        });
    }
};
