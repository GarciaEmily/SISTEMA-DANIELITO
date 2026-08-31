<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('visitas', function (Blueprint $table) {
        $table->id();

        $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
        $table->foreignId('realizado_por')->constrained('users')->onDelete('cascade');

        $table->date('fecha_visita');

        $table->text('motivo')->nullable();
        $table->text('observacion')->nullable();
        $table->text('seguimiento')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
