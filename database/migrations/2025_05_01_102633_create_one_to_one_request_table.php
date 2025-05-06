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
        Schema::create('oto_requests', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('ID of the user initiating the request');
            $table->string('met_with')->comment('ID of the user being met with');
            $table->string('initiated_by')->comment('ID of the user who initiated the meeting');
            $table->string('meeting_location')->comment('Location where the meeting will take place');
            $table->string('meeting_date')->comment('Date of the scheduled meeting');
            $table->text('topics')->nullable()->comment('Topics discussed during the meeting');
            $table->string('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oto_requests');
    }
};
