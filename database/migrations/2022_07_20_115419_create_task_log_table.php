<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->integer('arrive_on_site')->nullable();
            $table->integer('document_mileage')->nullable();
            $table->integer('call_local')->nullable();
            $table->string('task_status')->nullable();
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
        Schema::dropIfExists('task_log');
    }
}
