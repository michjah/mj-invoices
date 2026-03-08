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
            $table->string('invoice_id')->unique();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('duzp_date')->nullable();
            $table->enum('status', ['DRAFT', 'GENERATED', 'SENT'])->default('DRAFT');
            $table->string('order_id')->nullable();
            $table->string('note')->nullable();
            $table->decimal('total_price_without_vat', 10, 2)->default(0);
            $table->decimal('total_price_vat', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->integer('supplier_id');
            $table->integer('customer_id');
            $table->longText('invoice_pdf')->nullable();
            $table->foreign('supplier_id')->references('id')->on('contacts');
            $table->foreign('customer_id')->references('id')->on('contacts');
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
