<?php

namespace App\Services\Progres;

use App\Models\ImportBatch;
use App\Models\PaketProgres;
use App\Models\RawProgresRow;

class DataCleanerService
{
    public function process(ImportBatch $batch)
    {
        $sheets = RawProgresRow::where('import_batch_id', $batch->id)
            ->select('sheet_name')
            ->distinct()
            ->pluck('sheet_name');

        foreach ($sheets as $sheet) {
            $this->processSheet($batch, $sheet);
        }
        
        // Update batch counts
        $batch->update([
            'total_paket_detail' => PaketProgres::where('import_batch_id', $batch->id)->count(),
        ]);
    }

    protected function processSheet(ImportBatch $batch, $sheetName)
    {
        $rawRows = RawProgresRow::where('import_batch_id', $batch->id)
            ->where('sheet_name', $sheetName)
            ->orderBy('row_number')
            ->get();

        if ($rawRows->isEmpty()) return;

        $headerRowIndex = $this->detectHeader($rawRows);
        if ($headerRowIndex === null) return;

        $headerMap = $this->buildHeaderMap($rawRows[$headerRowIndex]->raw_data_json);

        $paketData = [];
        for ($i = $headerRowIndex + 1; $i < $rawRows->count(); $i++) {
            $rawRow = $rawRows[$i];
            $rowData = $rawRow->raw_data_json;

            $normalized = $this->normalizeColumns($headerMap, $rowData);
            $cleaned = $this->cleanData($normalized);

            if ($this->isPaketDetail($cleaned)) {
                $cleaned = $this->calculateMetrics($cleaned);
                $cleaned = $this->calculateRisk($cleaned);

                $paketData[] = [
                    'import_batch_id' => $batch->id,
                    'satker' => $sheetName,
                    'sheet_name' => $sheetName,
                    'row_number' => $rawRow->row_number,
                    'kode' => $cleaned['kode'] ?? null,
                    'paket' => $cleaned['paket'] ?? null,
                    'lokasi' => $cleaned['lokasi'] ?? null,
                    'jenis_paket' => $cleaned['jenis_paket'] ?? null,
                    'metode_pemilihan' => $cleaned['metode_pemilihan'] ?? null,
                    'sumber_dana' => $cleaned['sumber_dana'] ?? null,
                    'pagu' => $cleaned['pagu'] ?? null,
                    'realisasi' => $cleaned['realisasi'] ?? null,
                    'pagu_setelah_efisiensi' => $cleaned['pagu_setelah_efisiensi'] ?? null,
                    'blokir' => $cleaned['blokir'] ?? null,
                    'keuangan_percent' => $cleaned['keuangan_percent'] ?? null,
                    'fisik_percent' => $cleaned['fisik_percent'] ?? null,
                    'keuangan_setelah_efisiensi_percent' => $cleaned['keuangan_setelah_efisiensi_percent'] ?? null,
                    'fisik_setelah_efisiensi_percent' => $cleaned['fisik_setelah_efisiensi_percent'] ?? null,
                    'sisa_anggaran' => $cleaned['sisa_anggaran'] ?? null,
                    'serapan_terhadap_pagu' => $cleaned['serapan_terhadap_pagu'] ?? null,
                    'serapan_terhadap_pagu_efisiensi' => $cleaned['serapan_terhadap_pagu_efisiensi'] ?? null,
                    'gap_fisik_keuangan' => $cleaned['gap_fisik_keuangan'] ?? null,
                    'gap_keuangan_fisik' => $cleaned['gap_keuangan_fisik'] ?? null,
                    'status_risiko' => $cleaned['status_risiko'] ?? null,
                    'risk_score' => $cleaned['risk_score'] ?? 0,
                    'raw_payload_json' => json_encode($rowData),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($paketData)) {
            foreach (array_chunk($paketData, 500) as $chunk) {
                PaketProgres::insert($chunk);
            }
        }
    }

    protected function detectHeader($rawRows)
    {
        $keywords = ['kode', 'paket', 'kegiatan', 'lokasi', 'pagu', 'realisasi', 'keuangan', 'keu', 'fisik', 'fis'];
        
        for ($i = 0; $i < min(20, $rawRows->count()); $i++) {
            $rowData = $rawRows[$i]->raw_data_json;
            if (!is_array($rowData)) continue;

            $matches = 0;
            foreach ($rowData as $cell) {
                $cellStr = strtolower(trim((string)$cell));
                foreach ($keywords as $kw) {
                    if (str_contains($cellStr, $kw)) {
                        $matches++;
                        break;
                    }
                }
            }
            if ($matches >= 3) return $i;
        }
        return null;
    }

    protected function buildHeaderMap($headerRowData)
    {
        $map = [];
        if (!is_array($headerRowData)) return $map;
        foreach ($headerRowData as $index => $colName) {
            $map[$index] = strtolower(trim((string)$colName));
        }
        return $map;
    }

    protected function normalizeColumns($headerMap, $rowData)
    {
        $normalized = [];
        $keyMap = [
            'kode' => 'kode',
            'paket' => 'paket',
            'nama paket' => 'paket',
            'kegiatan' => 'paket',
            'lokasi' => 'lokasi',
            'jenis paket' => 'jenis_paket',
            'metode' => 'metode_pemilihan',
            'metode pemilihan' => 'metode_pemilihan',
            'sumber dana' => 'sumber_dana',
            'pagu' => 'pagu',
            'realisasi' => 'realisasi',
            'keu' => 'keuangan_percent',
            'keuangan' => 'keuangan_percent',
            'fis' => 'fisik_percent',
            'fisik' => 'fisik_percent',
            'pagu setelah efisiensi' => 'pagu_setelah_efisiensi',
            'keu setelah efisiensi' => 'keuangan_setelah_efisiensi_percent',
            'keuangan setelah efisiensi' => 'keuangan_setelah_efisiensi_percent',
            'fis setelah efisiensi' => 'fisik_setelah_efisiensi_percent',
            'fisik setelah efisiensi' => 'fisik_setelah_efisiensi_percent',
            'blokir' => 'blokir',
        ];

        if (!is_array($rowData)) return $normalized;

        foreach ($rowData as $index => $value) {
            $rawHeader = $headerMap[$index] ?? '';
            $mappedKey = null;

            // Exact match first
            foreach ($keyMap as $search => $replace) {
                if ($rawHeader === $search) {
                    $mappedKey = $replace;
                    break;
                }
            }

            // Fallback to partial match
            if (!$mappedKey) {
                foreach ($keyMap as $search => $replace) {
                    if (str_contains($rawHeader, $search)) {
                        $mappedKey = $replace;
                        break;
                    }
                }
            }

            if ($mappedKey && !isset($normalized[$mappedKey])) {
                $normalized[$mappedKey] = $value;
            }
        }

        $allKeys = array_unique(array_values($keyMap));
        foreach ($allKeys as $k) {
            if (!array_key_exists($k, $normalized)) {
                $normalized[$k] = null;
            }
        }

        return $normalized;
    }

    protected function cleanData($row)
    {
        $nullWords = ['nan', '-', '', 'null', 'none'];
        
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                $val = trim($value);
                if (in_array(strtolower($val), $nullWords, true)) {
                    $row[$key] = null;
                    continue;
                }

                $moneyCols = ['pagu', 'realisasi', 'pagu_setelah_efisiensi', 'blokir'];
                $percentCols = ['keuangan_percent', 'fisik_percent', 'keuangan_setelah_efisiensi_percent', 'fisik_setelah_efisiensi_percent'];

                if (in_array($key, $moneyCols)) {
                    $val = preg_replace('/[^0-9,\.-]/', '', $val);
                    if (preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d+)?$/', $val)) {
                        $val = str_replace('.', '', $val);
                        $val = str_replace(',', '.', $val);
                    }
                    $row[$key] = is_numeric($val) ? (float) $val : null;
                } elseif (in_array($key, $percentCols)) {
                    $val = str_replace('%', '', $val);
                    $row[$key] = is_numeric($val) ? (float) $val : null;
                } else {
                    $row[$key] = $val;
                }
            }
        }

        return $row;
    }

    protected function isPaketDetail($row)
    {
        $kode = $row['kode'] ?? '';
        $paket = strtoupper($row['paket'] ?? '');
        $lokasi = $row['lokasi'] ?? '';

        if (empty($kode) || empty($paket) || empty($lokasi)) return false;
        if (str_contains($paket, 'TOTAL') || str_contains($paket, 'SUBTOTAL')) return false;

        return true;
    }

    protected function calculateMetrics($row)
    {
        $pagu = $row['pagu'] ?? 0;
        $realisasi = $row['realisasi'] ?? 0;
        $paguEfisiensi = $row['pagu_setelah_efisiensi'] ?? $pagu;
        
        $row['pagu_setelah_efisiensi'] = $paguEfisiensi;
        $row['sisa_anggaran'] = max(0, $paguEfisiensi - $realisasi);

        $row['serapan_terhadap_pagu'] = $pagu > 0 ? ($realisasi / $pagu * 100) : 0;
        $row['serapan_terhadap_pagu_efisiensi'] = $paguEfisiensi > 0 ? ($realisasi / $paguEfisiensi * 100) : 0;

        $fisik = $row['fisik_setelah_efisiensi_percent'] ?? ($row['fisik_percent'] ?? 0);
        $keu = $row['keuangan_setelah_efisiensi_percent'] ?? ($row['keuangan_percent'] ?? 0);

        $row['gap_fisik_keuangan'] = $fisik - $keu;
        $row['gap_keuangan_fisik'] = $keu - $fisik;

        return $row;
    }

    protected function calculateRisk($row)
    {
        $realisasi = $row['realisasi'] ?? 0;
        $keu = $row['keuangan_setelah_efisiensi_percent'] ?? ($row['keuangan_percent'] ?? 0);
        $fisik = $row['fisik_setelah_efisiensi_percent'] ?? ($row['fisik_percent'] ?? 0);
        $gap = abs($row['gap_fisik_keuangan'] ?? 0);

        if ($realisasi == 0 || $keu < 70 || $fisik < 70) {
            $row['status_risiko'] = 'Kritis';
            $row['risk_score'] = 3;
            return $row;
        }

        if ($keu < 90 || $fisik < 90) {
            $row['status_risiko'] = 'Perlu Perhatian';
            $row['risk_score'] = 2;
            return $row;
        }

        if ($gap > 15) {
            $row['status_risiko'] = 'Perlu Review';
            $row['risk_score'] = 1;
            return $row;
        }

        $row['status_risiko'] = 'Aman';
        $row['risk_score'] = 0;
        return $row;
    }
}
