<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('room_name');
            $table->string('section');
            $table->enum('anterior', ['funciona', 'no-funciona', 'aislado', 'no-hay'])->nullable();
            $table->enum('nuevo', ['funciona', 'no-funciona', 'aislado', 'no-hay']);
            $table->text('observacion')->nullable();
            $table->timestamp('ts');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial');
    }
};
