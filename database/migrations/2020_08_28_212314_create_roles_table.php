<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    const TABLE_NAME = 'roles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('role_id')->primary();
            $table->string('role_name', 30);
            $table->uuid('store_id');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('deletion_token');

            // Add unique index
            $table->unique(['role_name', 'store_id', 'deletion_token'], 'unique_role_pre_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
