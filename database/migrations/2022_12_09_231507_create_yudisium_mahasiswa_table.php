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
        Schema::create('yudisium_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId("yudisium_id")->nullable()->constrained("yudisium")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->boolean('status_berkas')->default(0);
            $table->boolean('status_pinjam')->default(0);
            $table->boolean('status_final')->default(0);
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
        Schema::dropIfExists('yudisium_mahasiswa');
    }
};
