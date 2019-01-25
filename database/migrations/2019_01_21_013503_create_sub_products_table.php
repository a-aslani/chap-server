<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sub_products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('sub_products')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('has_subset')->default(0);
            $table->string('image');
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->string('description')->nullable();
            $table->string('working_time');
            $table->string('min_price');
            $table->boolean('approved')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sub_products');
    }
}
