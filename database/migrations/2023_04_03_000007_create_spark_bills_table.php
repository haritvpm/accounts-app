<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparkBillsTable extends Migration
{
    public function up()
    {
        Schema::create('spark_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('acquittance')->nullable();
            $table->string('sparkcode');
            $table->string('bill_type')->nullable();
            $table->timestamps();
        });
    }
}
