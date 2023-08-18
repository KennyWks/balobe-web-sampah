<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('user_details', function (Blueprint $table) {
            $table->uuid('user_detail_id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('jk', 9)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_hp', 12)->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('photo')->nullable();
            $table->longText('alamat')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE user_details ALTER COLUMN user_detail_id SET DEFAULT uuid_generate_v4();");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
