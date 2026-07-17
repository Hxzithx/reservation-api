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
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('booking_id')->nullable();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('customer_id')->nullable();

                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('total_price', 10, 2);
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'delivered',
                    'cancelled',
                ])->default('pending');
                $table->text('notes')->nullable();

                $table->boolean('is_delete')->default(false);
                $table->unsignedBigInteger('created_user')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('company_id')->references('id')->on('system_setting')->cascadeOnDelete();
                $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
                $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->foreign('customer_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
