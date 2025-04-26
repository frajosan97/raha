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
        Schema::create('mpesa_payments', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Keys (Relationships)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users.id
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade'); // Links to subscriptions.id

            // Transaction Identifiers
            $table->string('transaction_id')->unique(); // Mpesa transaction ID
            $table->string('reference')->nullable(); // Reference number for the transaction

            // User Details
            $table->string('phone_number')->nullable(); // User's phone number
            $table->string('name')->nullable(); // Name of the user or account holder

            // Transaction Details
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->string('status')->default('pending'); // e.g., 'pending', 'success', 'failed'

            // Timestamps
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_payments');
    }
};
