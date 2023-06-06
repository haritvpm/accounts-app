<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSparkBillDatasTable extends Migration
{
    public function up()
    {
        Schema::table('spark_bill_datas', function (Blueprint $table) {
            $table->unsignedBigInteger('sparkbill_id')->nullable();
            $table->foreign('sparkbill_id', 'sparkbill_fk_8271670')->references('id')->on('spark_bills');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_8271672')->references('id')->on('employees');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_8271669')->references('id')->on('users');
        });
    }
}
