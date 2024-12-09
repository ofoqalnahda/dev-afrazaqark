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
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->enum('type', ['App', 'AuthorityInvoice'])->default('App')->comment('Who pays: the application fee percentage or the authority invoice?');
            $table->decimal('amount', 20, 2)->default(0);
            $table->enum('status', ['paid','check','failed','unpaid'])->default('unpaid')->comment('Who pays: the application fee status?');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_payments');
    }
};
