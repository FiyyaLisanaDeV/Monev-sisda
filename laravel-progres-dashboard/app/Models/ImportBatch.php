<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function rawRows()
    {
        return $this->hasMany(RawProgresRow::class);
    }

    public function paketProgres()
    {
        return $this->hasMany(PaketProgres::class);
    }

    public function dataQualityReport()
    {
        return $this->hasOne(DataQualityReport::class);
    }
}
