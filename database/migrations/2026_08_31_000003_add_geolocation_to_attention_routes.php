<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attention_routes', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('phone');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('maps_url', 500)->nullable()->after('longitude');
            $table->string('schedule', 200)->nullable()->after('maps_url');
            $table->string('services', 500)->nullable()->after('schedule');
        });
    }

    public function down(): void
    {
        Schema::table('attention_routes', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'maps_url', 'schedule', 'services']);
        });
    }
};
