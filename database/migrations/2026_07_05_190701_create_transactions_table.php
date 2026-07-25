<?php

/**
 * Migración: Creación de tabla transactions.
 *
 * Crea la tabla de transacciones financieras con tipo (credito/debito),
 * monto, saldo acumulado, fecha, categoría y referencia. Relacionada
 * con users mediante clave foránea.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Ejecuta la migración */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->enum('type', ['credito', 'debito']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance', 12, 2)->default(0);
            $table->date('transaction_date');
            $table->string('category')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /** Revierte la migración */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
