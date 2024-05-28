<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('request_no');
            $table->string('gln_from');
            $table->string('gln_to');
            $table->date('date');
            $table->string('time');
            $table->text('note')->nullable();
            $table->integer('user_id')->default(0);
            $table->string('status')->default('pending');
            $table->json('items')->comment('items detail');
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
        Schema::dropIfExists('stock_transfers');
    }
}
