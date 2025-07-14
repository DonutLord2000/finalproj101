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
        Schema::table('threads', function (Blueprint $table) {
            // Make user_id nullable first if it's not already
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->boolean('is_anonymous')->default(false)->after('user_id');
        });

        Schema::table('comments', function (Blueprint $table) {
            // Make user_id nullable first if it's not already
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->boolean('is_anonymous')->default(false)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('is_anonymous');
            // Revert user_id to not nullable if it was originally so, use with caution
            // $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('is_anonymous');
            // Revert user_id to not nullable if it was originally so, use with caution
            // $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
