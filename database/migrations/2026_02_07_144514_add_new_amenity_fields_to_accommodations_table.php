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
            $table->integer('pool')->nullable()->default(0)->comment('Piscina');
            $table->integer('mini_pool')->nullable()->default(0)->comment('Mini Piscina');
            $table->integer('hydromassage')->nullable()->default(0)->comment('Hidromassagem');
            $table->integer('fireplace')->nullable()->default(0)->comment('Lareira');
            $table->integer('mezzanine')->nullable()->default(0)->comment('Mezanino');
            $table->integer('wine_cellar')->nullable()->default(0)->comment('Adega');
            $table->integer('wifi')->nullable()->default(0)->comment('Wifi');
            $table->integer('closet')->nullable()->default(0)->comment('Closet');
            $table->integer('breakfast_included')->nullable()->default(0)->comment('Café da manhã incluso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn([
                'pool',
                'mini_pool',
                'hydromassage',
                'fireplace',
                'mezzanine',
                'wine_cellar',
                'wifi',
                'closet',
                'breakfast_included',
            ]);
        });
    }
};
