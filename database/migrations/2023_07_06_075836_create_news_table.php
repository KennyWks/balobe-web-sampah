<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('news', function (Blueprint $table) {
            $table->uuid('news_id')->primary();
            $table->string('judul');
            $table->text('isi');
            $table->string('foto');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE news ALTER COLUMN news_id SET DEFAULT uuid_generate_v4();");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
