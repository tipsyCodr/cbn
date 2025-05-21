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
        Schema::create('expression_of_interests', function (Blueprint $table) {
            $table->id();
            $table->string('form_type')->nullable();
            $table->string('chapter_name');
            $table->string('region');
            $table->string('invited_by');
            $table->date('visit_date');
            $table->string('referral_source');
            $table->boolean('previous_member')->default(false);
            $table->string('full_name');
            $table->string('company_name');
            $table->string('professional_classification');
            $table->string('email');
            $table->string('mobile');
            $table->integer('experience_rating');
            $table->text('product_service_feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expression_of_interests');
    }
};
