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
            $table->string('customer_number');
            $table->string('invoice_number')->unique();
            $table->string('invoice_date');
            $table->string('invoice_due_date');
            $table->string('invoice_delivery_date');
            $table->string('invoice_title');
            $table->string('invoice_start_text');
            $table->json('invoice_positions');
            $table->string('invoice_payment_condition');
            $table->string('invoice_afterword');
            $table->string('invoice_path');
            $table->string('invoice_total');   
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
