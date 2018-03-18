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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->decimal('amount', 15, 2);
            $table->boolean('varying')->default(false);
            $table->dateTime('planned_at');
            $table->dateTime('actual_at')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('repeating_id')->unsigned()->nullable();
            $table->string('repeating_interval', 30)->nullable();

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
