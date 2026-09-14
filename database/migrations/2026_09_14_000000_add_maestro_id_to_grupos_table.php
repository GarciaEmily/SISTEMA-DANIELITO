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
        Schema::table('grupos', function (Blueprint $table) {
            // nullable + nullOnDelete: si se elimina el maestro, el grupo queda sin
            // maestro asignado en vez de arrastrar un borrado en cascada (ya se
            // bloquea la eliminación de un maestro con grupos asignados desde
            // UserController, pero la FK se deja segura de todos modos).
            $table->foreignId('maestro_id')
                ->nullable()
                ->after('descripcion')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('maestro_id');
        });
    }
};
