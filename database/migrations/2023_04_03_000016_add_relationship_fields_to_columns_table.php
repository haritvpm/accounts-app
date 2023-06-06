<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToColumnsTable extends Migration
{
    public function up()
    {
        Schema::table('columns', function (Blueprint $table) {
            $table->unsignedBigInteger('head_id')->nullable();
            $table->foreign('head_id', 'head_fk_8271640')->references('id')->on('heads');
        });
    }
}
