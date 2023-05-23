<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('role_id')->primary();
            $table->string('role');
            $table->string('deskripsi')->unique();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE roles ALTER COLUMN role_id SET DEFAULT uuid_generate_v4();");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
