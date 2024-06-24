<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    protected $dates = ['deleted_at'];

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('group_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('parent_memberID')->nullable();
            $table->string('slug')->nullable();
            $table->string('have_cr')->nullable();
            $table->string('cr_documentID')->nullable();
            $table->string('document_number')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('image')->nullable();
            $table->string('companyID')->nullable();
            $table->string('cr_number')->nullable();
            $table->string('cr_activity')->nullable();
            $table->string('company_name_eng')->nullable();
            $table->string('company_name_arabic')->nullable();
            $table->string('gcpGLNID')->nullable();
            $table->string('gln')->nullable();
            $table->date('gcp_expiry')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('code')->nullable();
            $table->json('settings')->nullable();
            $table->string('status')->default('active');
            $table->integer('user_id')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
