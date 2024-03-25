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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->double('balance_before')->default(0);
            $table->double('balance_after')->default(0);
            $table->double('amount')->default(0);
            $table->string('ref_number');
            $table->string('operator');
            $table->string('type');
            $table->string('Participant');
            $table->foreignId('wallet_id');
            $table->foreignId('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
