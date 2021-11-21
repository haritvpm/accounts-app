<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_bill_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('pay', 15, 2);
            $table->decimal('da', 15, 2);
            $table->decimal('hra', 15, 2);
             $table->decimal('other', 15, 2);
            $table->decimal('ota', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_bill_details');
    }
}
