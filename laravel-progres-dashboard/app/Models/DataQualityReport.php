<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataQualityReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'jumlah_paket_per_satker_json' => 'array',
        'warning_json' => 'array',
    ];

    public function importBatch()
    {
        return $this->belongsTo(ImportBatch::class);
    }
}
