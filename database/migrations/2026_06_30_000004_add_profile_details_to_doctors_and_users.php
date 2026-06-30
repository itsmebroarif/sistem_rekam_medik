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
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('duty_address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('duty_address');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('duty_address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('duty_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['duty_address', 'profile_photo']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'duty_address', 'profile_photo']);
        });
    }
};
