<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->required()->maxLength(255);
            $table->string('fabricante')->required()->maxLength(255);
            $table->integer('lote')->numeric()->required();
            $table->date('validade')->required();
            $table->integer('quantidade')->numeric()->required();
            $table->string('tipo')->required();
            $table->string('descricao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};