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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('group_id', 100)->unique();
            $table->string('chat_id', 100)->unique();
            $table->string('chat_name')->nullable();
            $table->string('phone', 40);
            $table->string('name');
            $table->boolean('is_one_day')->default(false);
            $table->boolean('active')->default(false);
            $table->string('schedule')->nullable();
            $table->string('inn')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
