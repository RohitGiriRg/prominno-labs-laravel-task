<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'seller'])
                  ->default('seller')
                  ->after('password');

            $table->string('mobile')->nullable()->after('role');
            $table->string('country')->nullable()->after('mobile');
            $table->string('state')->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'mobile', 'country', 'state']);
        });
    }
};
