<?php

namespace App\Services\Progres;

use App\Models\ImportBatch;
use App\Models\PaketProgres;

class DashboardQueryService
{
    protected ?int $importBatchId = null;

    public function __construct()
    {
        $latestBatch = ImportBatch::where('status', 'completed')->latest('id')->first();
        if ($latestBatch) {
            $this->importBatchId = $latestBatch->id;
        }
    }

    public function baseQuery()
    {
        return PaketProgres::query()
            ->when($this->importBatchId, fn($q) => $q->where('import_batch_id', $this->importBatchId));
    }

    public function getExecutiveKpis(): array
    {
        $q = $this->baseQuery();
        $totalPagu = (clone $q)->sum('pagu');
        $totalRealisasi = (clone $q)->sum('realisasi');
        $totalSisaAnggaran = (clone $q)->sum('sisa_anggaran');
        $serapan = $totalPagu > 0 ? ($totalRealisasi / $totalPagu * 100) : 0;
        
        $totalPaket = (clone $q)->count();
        $paketKritis = (clone $q)->where('status_risiko', 'Kritis')->count();
        $paketPerhatian = (clone $q)->where('status_risiko', 'Perlu Perhatian')->count();

        return [
            'totalPagu' => $totalPagu,
            'totalRealisasi' => $totalRealisasi,
            'totalSisaAnggaran' => $totalSisaAnggaran,
            'serapan' => $serapan,
            'totalPaket' => $totalPaket,
            'paketKritis' => $paketKritis,
            'paketPerhatian' => $paketPerhatian,
        ];
    }
}
