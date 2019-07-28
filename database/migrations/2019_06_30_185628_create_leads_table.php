<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('code')->autoIncrement();
            $table->string('company',225);
            $table->string('name',50);
            $table->string('email',100);
            $table->string('mphone',10);
            $table->string('address',100);
            $table->string('apartment',100)->nullable();
            $table->string('city',50);
            $table->string('state',3);
            $table->string('zip_code',10);
            $table->string('category',20);
            $table->string('make',20);
            $table->date('dob');
            $table->string('score',5);
            $table->string('amount',20);
            $table->string('remember_token', 100);
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
        Schema::dropIfExists('leads');
    }
}
