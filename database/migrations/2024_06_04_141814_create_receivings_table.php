<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivings', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->nullable();
            $table->integer('product_id');
            $table->string('productnameenglish');
            $table->string('sku');
            $table->string('barcode');
            $table->string('gln');
            $table->float('req_quantity');
            $table->float('receive_quantity');
            $table->date('date');
            $table->text('time');
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
        Schema::dropIfExists('receivings');
    }
}
