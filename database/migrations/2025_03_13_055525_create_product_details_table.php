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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('size_attribute_id');
            $table->foreign('size_attribute_id')->references('id')->on('product_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('color_attribute_id');
            $table->foreign('color_attribute_id')->references('id')->on('product_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sku');
            $table->string('unit_price');
            $table->string('quantity');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
