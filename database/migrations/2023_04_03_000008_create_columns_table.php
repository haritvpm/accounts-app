<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnsTable extends Migration
{
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('spark_title')->nullable();
            $table->string('title')->nullable();
            $table->string('fieldmapping');
            $table->timestamps();
        });
    }
}
