<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {


            $table->increments('id');


            $table->string('description', 150);
            $table->decimal('amount', 15, 2);
            $table->boolean('varying')->default(false);
            $table->date('planned_on');
            $table->date('actual_on')->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('repeating_id', 32)->nullable();
            $table->integer('repeating_interval')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->inUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
