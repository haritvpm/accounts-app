<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToHeadsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('heads', 'object_head')){

            Schema::table('heads', function (Blueprint $table) {
        
                $table->string('object_head')->unique();
                $table->string('object_head_name')->unique();
                $table->string('detail_head');

                
                    $table->dropColumn('head');
                
        
            });
        }
    }
}
