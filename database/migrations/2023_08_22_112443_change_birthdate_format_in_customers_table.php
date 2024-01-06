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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('birthdate', 10)->change(); // Assuming 10 characters can hold the new format
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->date('birthdate')->change();
        });
    }
};
