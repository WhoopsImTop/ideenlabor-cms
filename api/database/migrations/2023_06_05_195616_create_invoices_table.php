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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('customer_number');
            $table->string('invoice_number')->unique();
            $table->string('invoice_date');
            $table->string('deliveryDate');
            $table->string('due_date');
            $table->string('invoiceTitle');
            $table->string('invoiceStartText');
            $table->json('invoicePositions');
            $table->string('paymentCondition');
            $table->string('afterword');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
