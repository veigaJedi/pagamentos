<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_wallet');
            $table->decimal('value', 24,2)->default(0);
            $table->date('dt_transaction')->nullable();
            $table->string('status');
            $table->timestamps();
        });
        
        Schema::table('transactions', function($table) {
            $table->foreign('id_wallet')->references('id')->on('wallets')->onDelete('cascade');
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
