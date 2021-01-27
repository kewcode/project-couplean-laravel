<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); 
            $table->integer("seller")->nullable();  // for Dropshiping
            $table->integer("user_id"); 
            $table->integer("address_id"); 
            $table->integer("shipping"); 
            $table->integer("total"); 
            $table->string("payment_ref")->nullable(); // Ovo, Gopay, Dana, Shopee Pay, Bank Transfer
            $table->string("payment_file")->nullable(); // Bukti Pembayaran
            $table->string("resi")->nullable(); // 
            $table->integer("status")->default(1); // 1 Order, Bayar, Proses, Dikirim, Selesai 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
