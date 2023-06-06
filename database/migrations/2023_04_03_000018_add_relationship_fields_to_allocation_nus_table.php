<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAllocationNusTable extends Migration
{
    public function up()
    {
        Schema::table('allocation_nus', function (Blueprint $table) {
            $table->unsignedBigInteger('year_id')->nullable();
            $table->foreign('year_id', 'year_fk_8275507')->references('id')->on('years');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->foreign('head_id', 'head_fk_8275508')->references('id')->on('heads');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_8275512')->references('id')->on('users');
        });
    }
}
