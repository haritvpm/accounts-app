<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToHeadsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('heads', 'user_id')){
            Schema::table('heads', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id', 'user_fk_8271544')->references('id')->on('users');
            });
        }
    }
}
