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
        Schema::table('accommodations', function (Blueprint $table) {
            $table->after('status', function (Blueprint $table) {
                // Descrição e Comodidades
                $table->integer('double_bed')->default(0)->comment('Cama de casal');
                $table->integer('single_bed')->default(0)->comment('Cama de solteiro');
                $table->integer('air_conditioning')->default(0)->comment('Ar condicionado');
                $table->integer('bathroom')->default(0)->comment('Banheiro');
                $table->integer('tv')->default(0)->comment('TV');
                $table->integer('refrigerator')->default(0)->comment('Geladeira');
                $table->integer('cooktop')->default(0)->comment('Cooktop');
                $table->integer('microwave')->default(0)->comment('Microondas');
                $table->integer('coffee_maker')->default(0)->comment('Cafeteira');
                $table->integer('grill')->default(0)->comment('Grill');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn([
                'double_bed',
                'single_bed',
                'air_conditioning',
                'bathroom',
                'tv',
                'refrigerator',
                'cooktop',
                'microwave',
                'coffee_maker',
                'grill',
            ]);
        });
    }
};
