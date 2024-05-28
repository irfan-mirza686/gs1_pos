<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productnameenglish')->unique();
            $table->string('slug')->unique();
            $table->string('BrandName')->nullable();
            $table->integer('unit')->nullable();
            $table->float('purchase_price')->default(0);
            $table->float('selling_price')->default(0);
            $table->text('details_page')->nullable();
            $table->string('barcode')->nullable();
            $table->string('size')->nullable();
            $table->string('quantity')->nullable();
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->integer('user_id')->comment('Users')->default(0);
            $table->string('status')->default('inactive');
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
        Schema::dropIfExists('products');
    }
}
