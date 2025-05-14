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
        Schema::table('referral_history', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('referral_history', 'related_id')) {
                $table->unsignedBigInteger('related_id')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('referral_history', 'title')) {
                $table->string('title')->nullable()->after('related_id');
            }
            
            if (!Schema::hasColumn('referral_history', 'message')) {
                $table->text('message')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_history', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('referral_history', 'related_id')) {
                $table->dropColumn('related_id');
            }
            
            if (Schema::hasColumn('referral_history', 'title')) {
                $table->dropColumn('title');
            }
            
            if (Schema::hasColumn('referral_history', 'message')) {
                $table->dropColumn('message');
            }
        });
    }
};
