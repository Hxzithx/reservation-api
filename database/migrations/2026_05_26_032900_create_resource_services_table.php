<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id'); 
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->unique(['resource_id', 'service_id']);

            $table->foreign('resource_id')->references('id')->on('resources')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_services');
    }
};
