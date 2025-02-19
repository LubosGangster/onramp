<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['module_id']);
            }

            $table->dropColumn('module_id');
        });
    }

    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->after('language');
            $table->foreign('module_id')->references('id')->on('modules');
        });
    }
};
