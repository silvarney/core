<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('accommodation_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->onDelete('set null'); // Null = Standard Rate
            $table->decimal('price', 10, 2); // Override base_price
            $table->integer('min_nights')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
