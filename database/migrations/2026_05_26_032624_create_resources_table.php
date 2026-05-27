<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('resource_type');
            $table->string('resource_name', 80);
            $table->string('short_code', 10)->nullable()->unique();
            $table->integer('capacity')->default(1);
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('job_title')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('years_experience')->nullable();
            $table->json('languages')->nullable();
            $table->string('gender')->default('any');
            $table->text('bio')->nullable();
            $table->integer('display_order')->default(1);
            $table->json('data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('business_id')->references('id')->on('businesses')->cascadeOnDelete();
            $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};