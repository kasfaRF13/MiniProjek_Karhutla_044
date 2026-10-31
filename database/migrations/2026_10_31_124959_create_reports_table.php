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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('area_id')->constrained()->onDelete('cascade'); // Relasi ke tabel areas
            $table->string('location_detail'); // Patokan lokasi detail
            $table->decimal('latitude', 10, 8); // Validasi koordinat latitude
            $table->decimal('longitude', 11, 8); // Validasi koordinat longitude
            $table->dateTime('incident_time'); // Waktu kejadian
            $table->string('condition_level'); // Ringan, Sedang, Berat
            $table->text('description'); // Deskripsi kejadian
            $table->string('evidence_file')->nullable(); // Path file bukti (JPG/PNG/PDF)
            $table->string('status')->default('menunggu'); // Status: menunggu, ditinjau, diteruskan, selesai, ditolak
            $table->text('volunteer_notes')->nullable(); // Catatan dari relawan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
