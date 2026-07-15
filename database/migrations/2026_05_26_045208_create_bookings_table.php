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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('resource_id');

            $table->string('booking_reference')->unique();
            $table->enum('guest_booking', ['No , Linked to account', 'Yes , Guest only']);
            $table->enum('booking_channel', [
                'online',
                'walk_in',
                'phone',
                'api',
                'whatsapp',
            ])->default('online');

            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('number_of_guests')->default(1);
            $table->string('preferred_resource_type')->nullable();
            $table->enum('recurring', [
                'one_time',
                'daily',
                'weekly',
                'monthly',
            ])->default('one_time');

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');

            $table->text('special_notes')->nullable();
            $table->text('internal_staff_notes')->nullable();

            $table->enum('payment_type', [
                'full_payment',
                'deposit_only',
                'pay_on_arrival',
                'invoice',
            ])->default('full_payment');
            $table->enum('payment_method', [
                'Credit / Debit card',
                'Cash',
                'Bank transfer',
                'Online gateway',
                'wallet',
                'Contactless NFC',
            ])->default('Credit / Debit card');

            $table->string('discount_code')->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->enum('currency', [
                'LKR - Sri Lankan Rupee',
                'USD - US Dollar',
                'EUR - Euro',
                'AUD - Australian Dollar',
            ])->default('LKR - Sri Lankan Rupee');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('special_instructions')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('conpany_id')->references('id')->on('system_setting')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
            $table->foreign('resource_id')->references('id')->on('resources')->cascadeOnDelete();
            $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
