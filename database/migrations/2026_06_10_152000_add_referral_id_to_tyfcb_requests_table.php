<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tyfcb_requests', function (Blueprint $table) {
            $table->foreignId('referral_id')->nullable()->constrained('referrals')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tyfcb_requests', function (Blueprint $table) {
            $table->dropForeign(['referral_id']);
            $table->dropColumn('referral_id');
        });
    }
};
