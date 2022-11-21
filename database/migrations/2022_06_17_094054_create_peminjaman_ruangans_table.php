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
        Schema::create('peminjaman_ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("ruangan_id")->nullable()->constrained("ruangan")->onDelete("cascade")->onUpdate("cascade");
            $table->date('tanggal')->nullable();   
            $table->time('waktu_awal')->nullable();   
            $table->time('waktu_akhir')->nullable();   
            $table->text('keperluan')->nullable();
            $table->enum('status', ['Diterima','Ditolak','Menunggu'])->default('Menunggu');
            $table->text('catatan')->nullable();            
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
        Schema::dropIfExists('peminjaman_ruangan');
    }
};
