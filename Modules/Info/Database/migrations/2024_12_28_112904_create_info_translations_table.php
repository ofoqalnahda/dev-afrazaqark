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
        Schema::create('info_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale',5)->index();
            $table->unsignedBigInteger('info_id')->unsigned();
            $table->unique(['info_id', 'locale']);
            $table->foreign('info_id')->references('id')->on('infos')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_translations');
    }
};
