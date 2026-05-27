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
        Schema::table('resources', function (Blueprint $table) {
            $table->enum('resources_type', ['person', 'room', 'equipment', 'timeslot'])->after('business_id');
            $table->string('resource_name', 80);
            $table->string('short_code', 10)->nullable()->unique();
            $table->integer('capacity')->default(1);
            $table->json('photos')->nullable();
            $table->integer('display_order')->default(1);

            $table->json('data')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            //
        });
    }
};
