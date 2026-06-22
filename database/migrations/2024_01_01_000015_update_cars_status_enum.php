<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing data
        DB::table('cars')->whereIn('status', ['rented', 'maintenance'])->update(['status' => 'unavailable']);

        // Change enum
        Schema::table('cars', function (Blueprint $table) {
            $table->enum('status', ['available', 'unavailable'])->default('available')->change();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available')->change();
        });
    }
};
