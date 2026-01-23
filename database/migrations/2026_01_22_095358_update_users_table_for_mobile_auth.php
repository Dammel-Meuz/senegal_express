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
                $table->string('phone')->unique()->after('id');
                $table->enum('role', ['passenger', 'driver', 'admin'])->default('passenger');
                $table->boolean('is_active')->default(true);
                $table->timestamp('phone_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn([
            'phone',
            'email',
            'role',
            'is_active',
            'phone_verified_at'
        ]);        
        });
    }
};
