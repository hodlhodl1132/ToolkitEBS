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
        Schema::create('queued_polls', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('options');
            $table->integer('length');
            $table->integer('delay')->nullable();
            $table->boolean('validated')->default(false);
            $table->string('validation_error')->nullable();
            $table->integer('created_by_id');
            $table->string('provider_id');
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
        Schema::dropIfExists('poll_queue');
    }
};
