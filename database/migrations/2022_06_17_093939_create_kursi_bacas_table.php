<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('kursi_baca', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('kursi')->nullable();
            $table->foreignId("ruangan_baca_id")->nullable()->constrained("ruangan_baca")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kursi_baca');
    }
};
