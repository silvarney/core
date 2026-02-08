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
        Schema::table('bookings', function (Blueprint $table) {
            // Indexes for booking availability and performance queries
            $table->index(['accommodation_id', 'status', 'check_in', 'check_out'], 'bookings_availability_index');
            $table->index('user_id', 'bookings_user_index');
            $table->index('status', 'bookings_status_index');
            $table->index('payment_status', 'bookings_payment_status_index');
            $table->index(['check_in', 'check_out'], 'bookings_dates_index');
        });

        Schema::table('accommodations', function (Blueprint $table) {
            // Indexes for accommodation filtering and availability checks
            $table->index('status', 'accommodations_status_index');
            $table->index(['accommodation_type_id', 'status'], 'accommodations_type_status_index');
        });

        Schema::table('seasons', function (Blueprint $table) {
            // Indexes for season-based pricing queries
            $table->index(['start_date', 'end_date', 'active', 'priority'], 'seasons_date_priority_index');
            $table->index(['active', 'priority'], 'seasons_active_priority_index');
        });

        Schema::table('rates', function (Blueprint $table) {
            // Indexes for rate lookups
            $table->index(['accommodation_type_id', 'season_id'], 'rates_type_season_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_availability_index');
            $table->dropIndex('bookings_user_index');
            $table->dropIndex('bookings_status_index');
            $table->dropIndex('bookings_payment_status_index');
            $table->dropIndex('bookings_dates_index');
        });

        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropIndex('accommodations_status_index');
            $table->dropIndex('accommodations_type_status_index');
        });

        Schema::table('seasons', function (Blueprint $table) {
            $table->dropIndex('seasons_date_priority_index');
            $table->dropIndex('seasons_active_priority_index');
        });

        Schema::table('rates', function (Blueprint $table) {
            $table->dropIndex('rates_type_season_index');
        });
    }
};
