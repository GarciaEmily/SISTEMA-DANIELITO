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
        // Un niño queda sin grupo (grupo_id = null) cuando el grupo al que
        // pertenecía se desactiva (ver GrupoController::update), para que la
        // Directora/Administrador lo reasigne manualmente a un grupo activo.
        Schema::table('ninos', function (Blueprint $table) {
            $table->foreignId('grupo_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ninos', function (Blueprint $table) {
            $table->foreignId('grupo_id')->nullable(false)->change();
        });
    }
};
