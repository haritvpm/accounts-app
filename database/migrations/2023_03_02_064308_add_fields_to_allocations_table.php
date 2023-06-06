<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('allocations', 'created_by_id')){
                    
            Schema::table('allocations', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->foreign('created_by_id', 'created_by_fk_9706749')->references('id')->on('users');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allocations', function (Blueprint $table) {
            //
        });
    }
};
