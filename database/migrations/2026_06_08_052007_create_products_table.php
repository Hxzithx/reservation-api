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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');

            // Product Identity
            $table->string('name');
            $table->string('product_code')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->json('photos')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('currency', 10)->default('LKR');
            $table->decimal('tax_rate', 5, 2)->default(0);

            // Stock
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_alert')->default(5);
            $table->boolean('track_stock')->default(true);
            $table->enum('stock_status', [
                'in_stock',
                'low_stock',
                'out_of_stock',
            ])->default('in_stock');

            // Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('company_id')->references('id')->on('system_setting')->cascadeOnDelete();
            $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
