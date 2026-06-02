<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawProgresRow extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'raw_data_json' => 'array',
        'detected_header_json' => 'array',
    ];

    public function importBatch()
    {
        return $this->belongsTo(ImportBatch::class);
    }
}
