<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTdsTable extends Migration
{
    public function up()
    {
        Schema::create('tds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pan');
            $table->string('pen');
            $table->string('name');
            $table->decimal('gross', 15, 2);
            $table->decimal('tds', 15, 2);
            $table->integer('slno')->nullable();
            $table->timestamps();
        });
    }
}
