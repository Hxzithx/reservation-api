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
            $table->string('companycode')->after('id')->nullable();
            $table->string('FaxNo')->after('telephone')->nullable();
            $table->string('other_business_name_1')->after('name')->nullable();
            $table->string('other_business_name_2')->after('other_business_name_1')->nullable();
            $table->string('other_business_name_3')->after('other_business_name_2')->nullable();
            $table->unsignedBigInteger('contact_person')->after('website')->nullable();
            $table->string('tax_id_1')->after('contact_person')->nullable();
            $table->string('tax_id_2')->after('tax_id_1')->nullable();
            $table->string('tax_id_3')->after('tax_id_2')->nullable();
            $table->string('tax_id_4')->after('tax_id_3')->nullable();
            $table->string('tax_id_5')->after('tax_id_4')->nullable();
            $table->string('tax_registration_number_1')->after('tax_id_5')->nullable();
            $table->string('tax_registration_number_2')->after('tax_registration_number_1')->nullable();
            $table->string('tax_registration_number_3')->after('tax_registration_number_2')->nullable();
            $table->string('tax_registration_number_4')->after('tax_registration_number_3')->nullable();
            $table->string('tax_registration_number_5')->after('tax_registration_number_4')->nullable();
            $table->string('StartOfFiscalYear')->after('tax_registration_number_5')->nullable();
            $table->string('CostingMethod')->after('StartOfFiscalYear')->nullable();
            $table->boolean('isVat')->after('CostingMethod')->default(false)->nullable();
            $table->boolean('is_delete')->after('deleted_at')->default(false);
            $table->unsignedBigInteger('created_user')->after('created_at')->nullable();
            $table->unsignedBigInteger('update_by')->after('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
            'companycode',
            'FaxNo',
            'other_business_name_1',
            'other_business_name_2',
            'other_business_name_3',
            'contact_person',
            'tax_id_1',
            'tax_id_2',
            'taxt_id_3',
            'taxt_id_4',
            'taxt_id_5',
            'tax_registration_number_1',
            'tax_registration_number_2',
            'tax_registration_number_3',
            'tax_registration_number_4',
            'tax_registration_number_5',
            'StartOfFiscalYear',
            'CostingMethod',
            'isVat',
            'is_delete',
            'created_user',
            'update_by',
        ]);
        });
    }
};
