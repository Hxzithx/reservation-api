<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('name');
            $table->string('package_code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('package_type')->nullable();
            $table->string('badge')->nullable();
            $table->json('photos')->nullable();

            $table->decimal('original_price', 10, 2)->default(0);
            $table->decimal('package_price', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->string('currency', 10)->default('LKR');

            $table->json('services')->nullable();
            $table->json('resources')->nullable();
            $table->json('products')->nullable();

            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->integer('min_quantity')->default(1);
            $table->integer('max_quantity')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('business_id')->references('id')->on('businesses')->cascadeOnDelete();
            $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};