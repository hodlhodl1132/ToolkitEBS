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
        Schema::create('incident_defs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('mod_id');
            $table->string('def_name');
            $table->string('label');
            $table->string('letter_label');
            $table->string('letter_text');
            // Whether the incident is available as an option
            $table->boolean('enabled')->default(true);
            // Whether the incident is part of the active mod pack
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('incident_defs');
    }
};
