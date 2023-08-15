<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('transaction_id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('penampung_id')->nullable();
            $table->uuid('pengepul_id')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('status');
            $table->text('keterangan');
            $table->string('foto');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE transactions ALTER COLUMN transaction_id SET DEFAULT uuid_generate_v4();");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
