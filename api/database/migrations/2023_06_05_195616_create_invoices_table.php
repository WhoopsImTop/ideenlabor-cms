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
            $table->string('invoice_delivery_date')->nullable();
            $table->string('invoice_delivery_start_date')->nullable();
            $table->string('invoice_delivery_end_date')->nullable();
            $table->string('invoice_title');
            $table->string('invoice_start_text');
            $table->json('invoice_positions');
            $table->string('invoice_payment_condition')->nullable();
            $table->string('invoice_afterword')->nullable();
            $table->string('invoice_path')->nullable();
            $table->string('invoice_total');            
            $table->string('invoice_subtotal');
            $table->string('invoice_discount')->nullable();
            $table->string('invoice_shipping')->nullable();
            $table->string('invoice_tax');
            $table->string('invoice_status');
            $table->string('invoice_sent')->nullable();
            $table->string('invoice_send_date')->nullable();
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
