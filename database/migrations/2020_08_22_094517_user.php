<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    const TABLE_NAME = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('user_name', 20)->nullable();
            $table->string('email');
            $table->integer('age', false, true);
            $table->enum('gender', \App\Enums\GenderEnum::getMigrationValues());
            $table->timestamp('birthdate');
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_banned')->default(false);
            $table->string('deletion_token', 36)->default('N/A');

            // Add unique index
            $table->unique(['user_id'], 'unique_user_id');
            $table->unique(['email', 'deletion_token'], 'unique_email');
            $table->unique(['user_name', 'deletion_token'], 'unique_user_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->drop();
        });
    }
}
