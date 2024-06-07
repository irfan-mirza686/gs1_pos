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
            $table->string('type')->nullable();
            $table->string('gcpGLNID')->nullable();
            $table->string('productnameenglish')->nullable();
            $table->string('productnamearabic')->nullable();
            $table->string('slug')->nullable();
            $table->string('BrandName')->nullable();
            $table->string('BrandNameAr')->nullable();
            $table->string('ProductType')->nullable();
            $table->string('Origin')->nullable();
            $table->string('PackagingType')->nullable();
            $table->string('unit')->nullable();
            $table->string('size')->nullable();
            $table->string('gpc')->nullable();
            $table->string('gpc_code')->nullable();
            $table->string('countrySale')->nullable();
            $table->string('HSCODES')->nullable();
            $table->string('HsDescription')->nullable();
            $table->string('gcp_type')->nullable();
            $table->string('prod_lang')->nullable();
            $table->string('MnfCode')->nullable();
            $table->string('MnfGLN')->nullable();
            $table->string('ProvGLN')->nullable();
            $table->text('details_page')->nullable();
            $table->text('details_page_ar')->nullable();
            $table->string('product_url')->nullable();
            $table->float('purchase_price')->default(0);
            $table->float('selling_price')->default(0);
            $table->string('barcode')->nullable();
            $table->string('quantity')->nullable();
            $table->string('product_type')->nullable();
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
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
