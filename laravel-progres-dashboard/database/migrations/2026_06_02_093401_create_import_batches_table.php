<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('periode')->nullable();
            $table->string('tahun_anggaran')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->string('status')->default('uploaded'); // uploaded, processing, completed, completed_with_warning, failed
            $table->integer('total_sheets')->default(0);
            $table->integer('total_raw_rows')->default(0);
            $table->integer('total_paket_detail')->default(0);
            $table->integer('total_error_rows')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
