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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('password');
            $table->string('plan', 20)->default('free')->after('status');
            $table->string('role', 20)->default('member')->after('plan');
            $table->string('country', 2)->nullable()->after('role');
            $table->string('phone', 30)->nullable()->after('country');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->boolean('two_factor_enabled')->default(false)->after('phone_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'plan',
                'role',
                'country',
                'phone',
                'phone_verified_at',
                'two_factor_enabled',
                'last_login_at',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
