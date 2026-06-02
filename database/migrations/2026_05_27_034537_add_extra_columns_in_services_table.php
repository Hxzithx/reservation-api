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
        Schema::table('services', function (Blueprint $table) {
            $table->integer('duration')->default(60); // minutes
            $table->integer('buffer_after')->default(0); //minutes
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->string('category')->nullable();
            $table->string('location_notes')->nullable();

            //pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('member_price', 10, 2)->nullable(); //when members has special price
            $table->decimal('peak_price', 10, 2)->nullable(); //when price change in peak times (weekends, season)
            $table->decimal('deposit', 10, 2)->default(0)->nullable(); //how much customer have to pay before make an appointment 
            $table->decimal('group_price', 10, 2)->nullable(); //when customers book as a group the discounts price per person
            $table->enum('currency', ['USD', 'LKR', 'AED', 'EUR'])->default('USD');

            //availability
            $table->json('available_days'); //[MON,TUE,....]
            $table->time('available_from')->default('9:00:00');
            $table->time('available_until')->default('17:00:00');
            $table->enum('seasonal_availability', ['all_year', 'season', 'off_season'])->default('all_year');

            //booking rules
            $table->integer('max_advance_booking')->nullable()->default(60); //how many days ahead a customer can book
            $table->integer('min_notice')->nullable()->default(2); //how many hours before the appointment a customer must book
            $table->integer('cancellation_window')->nullable()->default(24); //cancel deadline for refund
            $table->integer('max_bookings_per_day')->nullable();
            $table->enum('confirmation', ['auto', 'manual'])->default('auto');
            $table->boolean('enable_waitlist')->default(false);
            $table->integer('min_group_size')->default(1);
            $table->integer('max_group_size')->default(1);

            //notifications
            $table->integer('remind_before_minutes')->nullable()->default(1440);

            // Custom booking requirements (dynamic questions)
            $table->json('booking_requirements')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            //
        });
    }
};
