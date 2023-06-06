<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSparkBillsTable extends Migration
{
    public function up()
    {
        Schema::table('spark_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('year_id')->nullable();
            $table->foreign('year_id', 'year_fk_8271608')->references('id')->on('years');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_8271612')->references('id')->on('users');
        });
    }
}
