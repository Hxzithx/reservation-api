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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('logo')->nullable();
            $table->string('currency', 10)->default('LKR');
            $table->string('timezone')->default('UTC');
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['logo', 'currency', 'timezone', 'website', 'tax_number']);
            $table->dropSoftDeletes();
        });
    }
};
