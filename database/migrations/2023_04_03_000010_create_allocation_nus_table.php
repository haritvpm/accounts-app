<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationNusTable extends Migration
{
    public function up()
    {
        Schema::create('allocation_nus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount');
            $table->timestamps();
        });
    }
}
