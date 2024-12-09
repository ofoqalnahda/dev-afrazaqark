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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('operation_type_id')->unsigned();
            $table->unsignedBigInteger('property_type_id')->unsigned();
            $table->unsignedBigInteger('route_type_id')->unsigned()->nullable();

            $table->integer('count_unit')->comment('count of unit');
            $table->unsignedBigInteger('status_id')->unsigned();
            $table->unsignedBigInteger('sub_status_id')->unsigned()->nullable();
            $table->unsignedBigInteger('area_id')->unsigned();
            $table->unsignedBigInteger('city_id')->unsigned();

            $table->boolean('is_matching')->default(true)->comment('Is the building in compliance with the construction permit?');
            $table->string('address',250)->nullable();
            $table->string('lat',50)->nullable();
            $table->string('lng',50)->nullable();


            $table->enum('cancel_by',['Admin'.'User'])->nullable();
            $table->unsignedBigInteger('cancel_by_id')->unsigned()->nullable();

            $table->unsignedBigInteger('reason_id')->unsigned()->nullable();
            $table->unsignedInteger('updated_by')->unsigned()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('operation_type_id')->references('id')->on('operation_types')->onDelete('cascade');
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
            $table->foreign('route_type_id')->references('id')->on('route_types')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('transaction_statuses')->onDelete('cascade');
            $table->foreign('reason_id')->references('id')->on('cancellation_reasons')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
