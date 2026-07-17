<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('imports', function (Blueprint $table) {
            // change date_reglement from date to string to allow free text
            $table->string('date_reglement')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->date('date_reglement')->nullable()->change();
        });
    }
};
