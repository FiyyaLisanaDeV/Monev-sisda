<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketProgres extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pagu' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'pagu_setelah_efisiensi' => 'decimal:2',
        'blokir' => 'decimal:2',
        'keuangan_percent' => 'decimal:2',
        'fisik_percent' => 'decimal:2',
        'keuangan_setelah_efisiensi_percent' => 'decimal:2',
        'fisik_setelah_efisiensi_percent' => 'decimal:2',
        'sisa_anggaran' => 'decimal:2',
        'serapan_terhadap_pagu' => 'decimal:2',
        'serapan_terhadap_pagu_efisiensi' => 'decimal:2',
        'gap_fisik_keuangan' => 'decimal:2',
        'gap_keuangan_fisik' => 'decimal:2',
        'raw_payload_json' => 'array',
        'cleaning_notes_json' => 'array',
    ];

    public function importBatch()
    {
        return $this->belongsTo(ImportBatch::class);
    }
}
