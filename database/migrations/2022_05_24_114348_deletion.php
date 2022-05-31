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
        DB::statement('ALTER TABLE `addresses` DROP FOREIGN KEY `addresses_user_id_foreign`;');
        DB::statement('ALTER TABLE `addresses` ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
        DB::statement('ALTER TABLE `statistics` DROP FOREIGN KEY `statistics_address_id_foreign`;');
        DB::statement('ALTER TABLE `statistics` ADD CONSTRAINT `statistics_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `addresses` DROP FOREIGN KEY `addresses_user_id_foreign`;');
        DB::statement('ALTER TABLE `addresses` ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;');
        DB::statement('ALTER TABLE `statistics` DROP FOREIGN KEY `statistics_address_id_foreign`;');
        DB::statement('ALTER TABLE `statistics` ADD CONSTRAINT `statistics_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;');
    }
};
