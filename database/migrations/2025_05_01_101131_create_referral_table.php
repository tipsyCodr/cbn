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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('from')->comment('User ID of the person who is referring');     
            $table->string('to')->comment('User ID of the person who is being referred');     
            $table->string('referral_type');
            $table->enum('referral_status', ['told_them_you_would_call', 'given_card']);
            $table->string('referral_contact_name');
            $table->string('referral_contact_no')->nullable();
            $table->string('referral_contact_email')->nullable();
            $table->string('mobile');
            $table->string('email');
            $table->string('address');
            $table->string('comments');
            $table->string('referral_hotness');
            $table->string('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
