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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->foreignId("kategori_id")->nullable()->constrained("kategori")->onDelete("cascade")->onUpdate("cascade");
            $table->string('tahun_terbit')->nullable();
            $table->string('nama_pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string('cover')->nullable();
            //new
            $table->string('lembar_pengesahan')->nullable();
            $table->string('kata_pengantar')->nullable();
            $table->string('ringkasan')->nullable();
            $table->string('daftar_isi')->nullable();
            $table->string('daftar_gambar')->nullable();
            $table->string('daftar_tabel')->nullable();
            $table->string('daftar_notasi')->nullable();    
            $table->string('abstract_en')->nullable();
            $table->string('abstract_id')->nullable();
            $table->string('bab1')->nullable();
            $table->string('bab2')->nullable();
            $table->string('bab3')->nullable();
            $table->string('bab4')->nullable();
            $table->string('kesimpulan')->nullable();
            $table->string('daftar_pustaka')->nullable();        
            $table->string('lampiran')->nullable();
            $table->string('paper')->nullable();
            $table->string('lembar_persetujuan')->nullable();
            $table->string('full_dokumen')->nullable();
            $table->enum('status',['Revisi','Diterima','Ditolak','Diproses'])->nullable()->default('Diproses');
            $table->longtext('data_tambahan')->nullable();
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
        Schema::dropIfExists('dokumen');
    }
};
