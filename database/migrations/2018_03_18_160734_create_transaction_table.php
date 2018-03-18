<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->decimal('amount', 15, 2);
            $table->boolean('varying');
            $table->dateTime('planned_at');
            $table->dateTime('actual_at');
            $table->integer('user_id')->unsigned();
            $table->integer('repeating_id')->unsigned();

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
        Schema::dropIfExists('transaction');
    }
}
