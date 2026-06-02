<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_batch_id')->constrained('import_batches')->cascadeOnDelete();
            $table->string('satker')->nullable()->index();
            $table->string('sheet_name')->nullable();
            $table->integer('row_number')->nullable();
        
            $table->string('kode')->nullable()->index();
            $table->text('paket')->nullable();
            $table->string('lokasi')->nullable()->index();
            $table->string('jenis_paket')->nullable()->index();
            $table->string('metode_pemilihan')->nullable();
            $table->string('sumber_dana')->nullable();
        
            $table->decimal('pagu', 20, 2)->nullable();
            $table->decimal('realisasi', 20, 2)->nullable();
            $table->decimal('pagu_setelah_efisiensi', 20, 2)->nullable();
            $table->decimal('blokir', 20, 2)->nullable();
        
            $table->decimal('keuangan_percent', 8, 2)->nullable();
            $table->decimal('fisik_percent', 8, 2)->nullable();
            $table->decimal('keuangan_setelah_efisiensi_percent', 8, 2)->nullable();
            $table->decimal('fisik_setelah_efisiensi_percent', 8, 2)->nullable();
        
            $table->decimal('sisa_anggaran', 20, 2)->nullable();
            $table->decimal('serapan_terhadap_pagu', 8, 2)->nullable();
            $table->decimal('serapan_terhadap_pagu_efisiensi', 8, 2)->nullable();
            $table->decimal('gap_fisik_keuangan', 8, 2)->nullable();
            $table->decimal('gap_keuangan_fisik', 8, 2)->nullable();
        
            $table->string('status_risiko')->nullable()->index();
            $table->integer('risk_score')->default(0)->index();
        
            $table->json('raw_payload_json')->nullable();
            $table->json('cleaning_notes_json')->nullable();
        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_progres');
    }
};
