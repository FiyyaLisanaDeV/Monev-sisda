<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_quality_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_batch_id')->constrained('import_batches')->cascadeOnDelete();
            $table->integer('total_raw_rows')->default(0);
            $table->integer('total_paket_detail')->default(0);
            $table->integer('baris_tanpa_kode')->default(0);
            $table->integer('baris_tanpa_lokasi')->default(0);
            $table->integer('baris_tanpa_pagu')->default(0);
            $table->integer('baris_realisasi_lebih_besar_dari_pagu')->default(0);
            $table->integer('baris_pagu_nol_realisasi_ada')->default(0);
            $table->integer('baris_keuangan_kosong')->default(0);
            $table->integer('baris_fisik_kosong')->default(0);
            $table->json('jumlah_paket_per_satker_json')->nullable();
            $table->json('warning_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_quality_reports');
    }
};
