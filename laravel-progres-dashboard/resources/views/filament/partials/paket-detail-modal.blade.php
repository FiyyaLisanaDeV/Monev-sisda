@php
    $status = $record->status_risiko ?? '-';
    $statusColor = match ($status) {
        'Kritis' => 'var(--dash-danger)',
        'Perlu Perhatian' => 'var(--dash-primary-strong)',
        'Perlu Review' => '#3b82f6',
        'Aman' => '#5f5e5f',
        default => '#6b7280',
    };

    $pagu = (float) ($record->pagu_setelah_efisiensi ?? 0);
    $realisasi = (float) ($record->realisasi ?? 0);
    $sisaAnggaran = (float) ($record->sisa_anggaran ?? 0);
    $keuangan = (float) ($record->keuangan_setelah_efisiensi_percent ?? $record->keuangan_percent ?? 0);
    $fisik = (float) ($record->fisik_setelah_efisiensi_percent ?? $record->fisik_percent ?? 0);
    $gap = (float) ($record->gap_fisik_keuangan ?? 0);

    $keuanganProgress = max(0, min(100, $keuangan));
    $fisikProgress = max(0, min(100, $fisik));

    $riskSummary = match (true) {
        $status === 'Kritis', $realisasi == 0, $fisik < 70, $gap < -15 => 'Risiko tinggi',
        $status === 'Perlu Perhatian', $fisik < 90, abs($gap) > 15 => 'Perlu perhatian',
        default => 'Relatif aman',
    };

    $riskDescription = match (true) {
        $realisasi == 0 => 'Realisasi masih nol, sehingga proyek belum bergerak.',
        $fisik < 70 && $keuangan >= 90 => 'Serapan keuangan sudah tinggi tetapi progres fisik tertinggal.',
        $gap < -15 => 'Deviasi fisik-keuangan terlalu lebar dan perlu tindak lanjut.',
        $gap > 15 => 'Progres fisik lebih cepat daripada serapan keuangan.',
        default => 'Angka utama masih dalam batas yang dapat dipantau.',
    };

    $rows = [
        'Satker' => $record->satker ?? '-',
        'Lokasi' => $record->lokasi ?? '-',
        'Kode Paket' => $record->kode ?? '-',
        'Jenis Paket' => $record->jenis_paket ?? '-',
        'Nama Paket' => $record->paket ?? '-',
        'Pagu Efisiensi' => 'Rp ' . number_format($pagu, 0, ',', '.'),
        'Realisasi' => 'Rp ' . number_format($realisasi, 0, ',', '.'),
        'Sisa Anggaran' => 'Rp ' . number_format($sisaAnggaran, 0, ',', '.'),
        'Keuangan (%)' => number_format($keuangan, 2) . '%',
        'Fisik (%)' => number_format($fisik, 2) . '%',
        'Gap / Deviasi (%)' => number_format($gap, 2) . '%',
    ];
@endphp

<div class="paket-detail-modal">
    <section class="dashboard-card paket-detail-modal__hero">
        <div class="paket-detail-modal__hero-grid">
            <div class="paket-detail-modal__hero-copy">
                <span class="dashboard-card__eyebrow">Detail Paket</span>
                <h3 class="dashboard-card__title">{{ $record->paket ?? 'Tanpa Nama Paket' }}</h3>
            </div>

            <div class="paket-detail-modal__status" style="background: {{ $statusColor }}">
                {{ $status }}
            </div>
        </div>
    </section>

    <section class="dashboard-card">
        <div class="paket-detail-modal__risk">
            <div class="paket-detail-modal__risk-header">
                <div>
                    <span class="dashboard-card__eyebrow">Ringkasan Risiko</span>
                    <h4 class="dashboard-card__title">{{ $riskSummary }}</h4>
                    <p class="dashboard-card__meta mb-0">{{ $riskDescription }}</p>
                </div>

                <div class="paket-detail-modal__gap">
                    <span>Gap / Deviasi</span>
                    <strong>{{ number_format($gap, 2) }}%</strong>
                </div>
            </div>

            <div class="paket-detail-modal__progress-grid">
                <div class="paket-detail-modal__progress-card">
                    <div class="paket-detail-modal__progress-head">
                        <span>Keuangan</span>
                        <strong>{{ number_format($keuangan, 2) }}%</strong>
                    </div>
                    <div class="paket-detail-modal__progress-track">
                        <div class="paket-detail-modal__progress-bar paket-detail-modal__progress-bar--finance" style="width: {{ $keuanganProgress }}%"></div>
                    </div>
                    <p>Realisasi anggaran yang sudah tercatat.</p>
                </div>

                <div class="paket-detail-modal__progress-card">
                    <div class="paket-detail-modal__progress-head">
                        <span>Fisik</span>
                        <strong>{{ number_format($fisik, 2) }}%</strong>
                    </div>
                    <div class="paket-detail-modal__progress-track">
                        <div class="paket-detail-modal__progress-bar {{ $fisik < 70 ? 'paket-detail-modal__progress-bar--danger' : 'paket-detail-modal__progress-bar--field' }}" style="width: {{ $fisikProgress }}%"></div>
                    </div>
                    <p>Progres pekerjaan di lapangan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="paket-detail-modal__table-wrap">
        <table class="paket-detail-modal__table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $label => $value)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
