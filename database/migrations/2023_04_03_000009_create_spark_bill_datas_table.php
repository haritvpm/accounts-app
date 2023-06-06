<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparkBillDatasTable extends Migration
{
    public function up()
    {
        Schema::create('spark_bill_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('gross', 15, 2)->nullable();
            $table->decimal('net', 15, 2)->nullable();
            $table->decimal('field_1', 15, 2)->nullable();
            $table->decimal('field_2', 15, 2)->nullable();
            $table->decimal('field_3', 15, 2)->nullable();
            $table->decimal('field_4', 15, 2)->nullable();
            $table->decimal('field_5', 15, 2)->nullable();
            $table->decimal('field_6', 15, 2)->nullable();
            $table->decimal('field_7', 15, 2)->nullable();
            $table->decimal('field_8', 15, 2)->nullable();
            $table->decimal('field_9', 15, 2)->nullable();
            $table->decimal('field_10', 15, 2)->nullable();
            $table->decimal('field_11', 15, 2)->nullable();
            $table->decimal('field_12', 15, 2)->nullable();
            $table->decimal('field_13', 15, 2)->nullable();
            $table->decimal('field_14', 15, 2)->nullable();
            $table->decimal('field_15', 15, 2)->nullable();
            $table->decimal('field_16', 15, 2)->nullable();
            $table->decimal('field_17', 15, 2)->nullable();
            $table->decimal('field_18', 15, 2)->nullable();
            $table->decimal('field_19', 15, 2)->nullable();
            $table->decimal('field_20', 15, 2)->nullable();
            $table->decimal('field_21', 15, 2)->nullable();
            $table->decimal('field_22', 15, 2)->nullable();
            $table->decimal('field_23', 15, 2)->nullable();
            $table->decimal('field_24', 15, 2)->nullable();
            $table->decimal('field_25', 15, 2)->nullable();
            $table->decimal('field_26', 15, 2)->nullable();
            $table->timestamps();
        });
    }
}
