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

<div class="space-y-6 pt-6 pb-4">
    <div class="dashboard-card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <span class="dashboard-card__eyebrow">Detail Paket</span>
                <h3 class="dashboard-card__title text-2xl">{{ $record->paket ?? 'Tanpa Nama Paket' }}</h3>
                <p class="dashboard-card__meta mb-0">Informasi ringkas yang tetap terbaca di layar kecil.</p>
            </div>

            <div class="px-3 py-1.5 rounded-full text-xs font-bold tracking-[0.08em] uppercase text-white"
                 style="background: {{ $statusColor }}">
                {{ $status }}
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <span class="dashboard-card__eyebrow">Ringkasan Risiko</span>
                    <h4 class="dashboard-card__title text-xl mb-1">{{ $riskSummary }}</h4>
                    <p class="dashboard-card__meta mb-0">{{ $riskDescription }}</p>
                </div>

                <div class="px-3 py-2 rounded-2xl border border-[rgba(213,196,172,0.5)] bg-[rgba(251,183,23,0.08)] text-[var(--dash-primary)]">
                    <div class="text-[0.72rem] uppercase tracking-[0.08em] font-bold">Gap / Deviasi</div>
                    <div class="text-2xl font-extrabold leading-none mt-1">{{ number_format($gap, 2) }}%</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-[rgba(227,225,236,0.95)] bg-white p-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span class="text-xs font-bold uppercase tracking-[0.08em] text-[var(--dash-text-soft)]">Keuangan</span>
                        <strong class="text-sm text-[var(--dash-text)]">{{ number_format($keuangan, 2) }}%</strong>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-[rgba(227,225,236,0.9)]">
                        <div class="h-full rounded-full bg-[linear-gradient(90deg,#fbb717,#ffd36b)]" style="width: {{ $keuanganProgress }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-[var(--dash-text-soft)]">
                        Realisasi anggaran yang sudah tercatat.
                    </p>
                </div>

                <div class="rounded-2xl border border-[rgba(227,225,236,0.95)] bg-white p-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span class="text-xs font-bold uppercase tracking-[0.08em] text-[var(--dash-text-soft)]">Fisik</span>
                        <strong class="text-sm text-[var(--dash-text)]">{{ number_format($fisik, 2) }}%</strong>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-[rgba(227,225,236,0.9)]">
                        <div class="h-full rounded-full {{ $fisik < 70 ? 'bg-[linear-gradient(90deg,#ba1a1a,#ef4444)]' : 'bg-[linear-gradient(90deg,#7c5800,#fbb717)]' }}" style="width: {{ $fisikProgress }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-[var(--dash-text-soft)]">
                        Progres pekerjaan di lapangan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-[rgba(227,225,236,0.95)] bg-white shadow-[0_18px_40px_-24px_rgb(26_27_34/0.35)]">
        <table class="min-w-full divide-y divide-[rgba(227,225,236,0.8)]">
            <thead class="bg-[linear-gradient(180deg,rgba(244,242,253,0.88),rgba(238,237,247,0.88))]">
                <tr>
                    <th class="px-4 py-3 text-left text-[0.72rem] font-bold uppercase tracking-[0.08em] text-[var(--dash-text-soft)]">Field</th>
                    <th class="px-4 py-3 text-left text-[0.72rem] font-bold uppercase tracking-[0.08em] text-[var(--dash-text-soft)]">Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[rgba(227,225,236,0.75)]">
                @foreach ($rows as $label => $value)
                    <tr class="align-top">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-[var(--dash-text)]">
                            {{ $label }}
                        </td>
                        <td class="px-4 py-3 text-sm text-[var(--dash-text-soft)] break-words">
                            {{ $value }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
