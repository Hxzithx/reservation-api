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
            $table->unsignedBigInteger('company_id');
            $table->string('resource_type');
            $table->string('resource_name', 80);
            $table->string('short_code', 10)->nullable()->unique();
            $table->integer('capacity')->default(1);
            $table->json('photos')->nullable();
            $table->integer('display_order')->default(1);
            $table->text('description')->nullable();

            //staff
            $table->string('job_title')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('years_experience')->nullable();
            $table->json('languages')->nullable();
            $table->string('gender')->default('any');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('bio')->nullable();

            //room
            $table->string('room_type')->nullable();
            $table->string('floor')->nullable();
            $table->decimal('area', 8, 2)->nullable();
            $table->enum('pricing_model', ['Per night', 'Per hour', 'Per day', 'Per event'])->nullable()->default('Per night');
            $table->integer('min_stay')->nullable();
            $table->string('amenities')->nullable();

            //equipment
            $table->enum('equipment_category', ['Audio / PA', 'Lighting', 'Medical device', 'Kitchen equipment', 'Sport gear', 'Other'])->default('Audio / PA');
            $table->string('brand_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('condition', ['Excellent', 'Good', 'Fair', 'Needs to service'])->default('Excellent'); 
            $table->decimal('hire_rate', 10, 2)->nullable();
            $table->decimal('deposit_required', 10, 2)->nullable();

            //time slot
            $table->enum('slot_category', ['Appointment', 'Class / Group session', 'Consultation', 'Delivery / pickup'])->default('Appointment'); 
            $table->enum('virtual_type', ['Virtual only', 'In-person only', 'Both available'])->default('Virtual only');
            $table->string('video_link')->nullable();
            $table->integer('max_group_size')->nullable();

            $table->json('custom_attributes')->nullable();

            $table->decimal('base_price', 10, 2)->default(0);
            $table->enum('pricing_unit', ['Fixed price', 'Per hour', 'Per hour', 'Per day/Overnight', 'Per stay']);
            $table->integer('buffer_after')->nullable()->default(0);
            $table->integer('cancellation_window')->nullable()->default(24);
            $table->integer('max_bookings_per_day')->nullable();
            $table->integer('min_notice')->nullable()->default(2);
            
            $table->boolean('is_active')->default(true);
            $table->boolean('require_approval')->default(false); 
            $table->boolean('allow_reschedule')->default(true);  
            $table->boolean('allow_online_payment')->default(true);
            $table->boolean('allow_pay_on_arrival')->default(false); 
            $table->boolean('send_auto_reminders')->default(true); 
            $table->boolean('request_review_after')->default(true);

            $table->json('data')->nullable();

            $table->boolean('is_delete')->default(false);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('company_id')->references('id')->on('system_setting')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};