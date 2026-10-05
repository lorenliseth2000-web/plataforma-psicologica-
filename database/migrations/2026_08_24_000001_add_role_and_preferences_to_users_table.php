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
            $table->string('role')->default('user')->after('password'); // 'user', 'admin'
            $table->json('preferences')->nullable()->after('role');
            $table->date('birthdate')->nullable()->after('preferences');
            $table->string('occupation')->nullable()->after('birthdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'preferences', 'birthdate', 'occupation']);
        });
    }
};
