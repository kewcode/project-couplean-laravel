<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVarianProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('varian_products', function (Blueprint $table) {
            $table->id();
            $table->integer("product_id"); 
            $table->string("img")->nullable(); 
            $table->string("name"); 
            $table->integer("stock"); 
            $table->integer("price"); 
            $table->integer("discount"); 
            $table->string("size")->nullable(); 
            $table->string("color")->nullable(); 
            $table->text("description"); 
            $table->integer("seller_price"); 
            $table->string("seller_contact"); 
            $table->integer("sold")->nullable();
            $table->boolean("status");  // Aktif / No
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('varian_products');
    }
}
