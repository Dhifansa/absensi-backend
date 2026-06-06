<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('sesi_qr', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_sesi', ['Hari H', 'Gladi']);
            $table->string('token', 64)->unique();
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->index('token');
            $table->index('expired_at');
        });

        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nic', 10);
            $table->string('divisi', 50);
            $table->enum('jenis_sesi', ['Hari H', 'Gladi']);
            $table->timestamp('waktu')->useCurrent();
            $table->timestamps();

            $table->unique(['nic', 'divisi', 'jenis_sesi']);
            $table->index('jenis_sesi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('sesi_qr');
        Schema::dropIfExists('admins');
    }
};
