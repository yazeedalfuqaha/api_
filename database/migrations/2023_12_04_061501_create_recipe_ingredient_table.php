<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('recipe_ingredient', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('recipe_id');
        $table->unsignedBigInteger('ingredient_id');
        $table->float('amount'); // Adjust the data type as needed

        $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredient');
    }
};
