<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 255);
           $table->string('latitude', 255)->nullable();
             $table->string('longitude', 255)->nullable();
            $table->string('desa', 255)->nullable();
            $table->string('kec', 255)->nullable();
            $table->string('kab', 255)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('foto_sekolah')->nullable();
            $table->text('foto_kantin')->nullable();
                 $table->string('jumlah_siswa', 255)->nullable();
                   $table->string('jumlah_guru', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
