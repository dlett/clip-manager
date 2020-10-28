<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowLogoUrlNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcasters', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->change();
        });
        Schema::table('curators', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('broadcasters', function (Blueprint $table) {
            $table->string('logo_url')->change();
        });
        Schema::table('broadcasters', function (Blueprint $table) {
            $table->string('curators')->change();
        });
    }
}
