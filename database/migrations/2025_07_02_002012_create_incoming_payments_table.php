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
        Schema::create('incoming_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();

            $table->string('chat_id')->nullable();
            $table->string('group_id')->nullable();

            $table->string('sender_name')->nullable();
            $table->string('sender_bank')->nullable();
            $table->string('sender_card')->nullable();
            $table->float('sum')->nullable();

            $table->string('status')->default('new');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_bank')->nullable();
            $table->string('recipient_card')->nullable();
            $table->string('recipient_iban')->nullable();
            $table->float('incoming_sum')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_payments');
    }
};
