<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRolePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id', 'role_id_fk_5358879')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id', 'permission_id_fk_5358879')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role_pivot');
    }
}
