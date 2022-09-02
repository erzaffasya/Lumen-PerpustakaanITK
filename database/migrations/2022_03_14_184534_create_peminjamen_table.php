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
        Schema::create('peminjaman_dokumen', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl_peminjaman')->nullable();
            $table->dateTime('tgl_pengembalian')->nullable();            
            $table->boolean('status')->nullable();            
            $table->foreignId("dokumen_id")->nullable()->constrained("dokumen")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('peminjaman_dokumen');
    }
};
