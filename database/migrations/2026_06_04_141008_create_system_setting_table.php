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
        Schema::create('system_setting', function (Blueprint $table) {
            $table->id();

            $table->string('company_name');
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('website')->nullable();

            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('province_state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            $table->enum('system_currency', ['LKR', 'USD', 'EUR', 'AED'])->default('LKR');

            $table->enum('header_display_type', [
                'company_logo',
                'custom_text'
            ])->default('company_logo');

            $table->string('company_logo')->nullable();
            $table->string('company_slogan')->nullable();
            $table->integer('slogan_font_size')->default(12);
            $table->string('document_header_text')->nullable();
            $table->integer('header_font_size')->default(24);

            $table->string('theme_color')->default('#4F46E5');

            $table->text('bank_details')->nullable();

            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_setting');
    }
};
