<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name')->unique();
            // $table->string('u_uname');
            $table->text('description');
            // $table->unsignedBigInteger('role_id');
            // $table->string('f_name');
            // $table->string('m_name');
            // $table->string('l_name');
            // $table->string('number');
            // $table->string('designation');
            // $table->string('address');
            $table->string('state');
            $table->string('District');
            $table->string('taluka');
            $table->string('village');
            $table->text('start_date');
            $table->text('end_date');
            $table->string('latitude');
            $table->string('longitude');
            // $table->string('user_agent')->default('null');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('projects');
    }
}