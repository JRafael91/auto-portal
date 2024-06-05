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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32);
            $table->date('info_date');
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->string('year', 5);
            $table->string('customer');
            $table->decimal('total', 30)->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['GENERADO', 'PROCESO', 'FINALIZADO', 'CANCELADO'])->default('GENERADO');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
