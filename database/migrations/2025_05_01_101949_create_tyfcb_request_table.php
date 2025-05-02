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
        Schema::create('tyfcb_requests', function (Blueprint $table) {
            $table->id();
            $table->string('from')->comment('User id of the user who is thanking');
            $table->string('to')->comment('User who to send the request');
            $table->enum('business_type', ['new','repeat'])->default('new');
            $table->enum('referral_type',['inside','outside','tier3'])->default('outside');
            $table->string('comments');
            $table->string('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyfcb_requests');
    }
};
