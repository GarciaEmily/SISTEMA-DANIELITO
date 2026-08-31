<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_grupo', function (Blueprint $table) {

            $table->id();

            $table->foreignId('actividad_id')
                ->constrained('actividades')
                ->onDelete('cascade');

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->onDelete('cascade');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_grupo');
    }
};