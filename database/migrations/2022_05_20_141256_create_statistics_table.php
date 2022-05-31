<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('address_id')->references('id')->on('addresses');
            $table->ipAddress('ip')->nullable();

        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedInteger('clicks')->default(0)->after('not_in_use');
        });
        DB::statement('ALTER TABLE `addresses` MODIFY COLUMN `deleted_at` timestamp NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('clicks');
        });
        DB::statement('ALTER TABLE `addresses` MODIFY COLUMN `deleted_at` timestamp NULL AFTER `not_in_use`');
    }
};
