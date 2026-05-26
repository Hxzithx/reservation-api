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
            $table->string('address_line_1')->after('address')->nullable();
            $table->string('address_line_2')->after('address_line_1')->nullable();
            $table->string('address_line_3')->after('address_line_2')->nullable();
            $table->string('city')->after('address_line_3')->nullable();
            $table->string('telephone')->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['address_line_1', 'address_line_2', 'address_line_3', 'city', 'telephone']);
        });
    }
};
