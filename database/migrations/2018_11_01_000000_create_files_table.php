<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        /*
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('attachable');
            $table->string('path');
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('size')->nullable();
            $table->unsignedInteger('order')->nullable();
            $table->nullableTimestamps();
        });*/

        /*For file library...*/
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('path');
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('size')->nullable();
            $table->unsignedInteger('order')->nullable();
            
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->nullableTimestamps();
        });
        Schema::create('attachables', function(Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->morphs('attachable');
            $table->bigInteger('file_id')->unsigned()->index();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('attachables');
        Schema::dropIfExists('files');
    }
}
