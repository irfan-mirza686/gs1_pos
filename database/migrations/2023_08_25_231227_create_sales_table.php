<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_no')->unique();
            $table->integer('customer_id');
            $table->date('date');
            $table->text('description')->nullable();
            $table->json('items')->comment('items detail');
            $table->string('sale_type')->comment('new/return');
            $table->string('status');
            $table->float('total');
            $table->float('paid_amount');
            $table->integer('user_id');
            $table->softDeletes();
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
        Schema::dropIfExists('sales');
    }
}
