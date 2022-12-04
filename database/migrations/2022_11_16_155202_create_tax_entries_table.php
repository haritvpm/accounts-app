<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('tax_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('status')->nullable();
            $table->string('acquittance')->nullable();
            $table->timestamps();
        });
    }
}
