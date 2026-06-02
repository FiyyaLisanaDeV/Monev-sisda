<div class="dashboard-hero">
    <div class="dashboard-hero__content">
        <div class="dashboard-eyebrow">
            Pantauan Eksekutif
        </div>

        <h1 class="dashboard-title">
            Ringkasan Eksekutif
        </h1>

        <p class="dashboard-subtitle">
            Pelacakan anggaran, realisasi, dan risiko paket dalam satu tampilan yang cepat dibaca.
        </p>

        <div class="dashboard-chip-row">
            <div class="dashboard-chip dashboard-chip--accent">
                <span class="material-symbols-outlined">calendar_today</span>
                <span>Tahun Anggaran {{ $tahunAnggaran }}</span>
            </div>

            <div class="dashboard-chip">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>{{ number_format($totalPaket) }} paket aktif</span>
            </div>

            <div class="dashboard-chip">
                <span class="material-symbols-outlined">warning</span>
                <span>{{ number_format($paketKritis) }} paket kritis</span>
            </div>
        </div>
    </div>

    <div class="dashboard-hero__panel">
        <div class="dashboard-mini-grid">
            <div class="dashboard-mini-card">
                <span class="dashboard-mini-label">Total Pagu</span>
                <strong>Rp {{ number_format($totalPagu, 0, ',', '.') }}</strong>
                <small>Dana dialokasikan</small>
            </div>

            <div class="dashboard-mini-card dashboard-mini-card--success">
                <span class="dashboard-mini-label">Total Realisasi</span>
                <strong>Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</strong>
                <small>Sudah tersalurkan</small>
            </div>

            <div class="dashboard-mini-card dashboard-mini-card--highlight">
                <span class="dashboard-mini-label">Persentase Serapan</span>
                <strong>{{ number_format($serapan, 2) }}%</strong>
                <small>Perbandingan realisasi terhadap pagu</small>
            </div>
        </div>

        <div class="dashboard-hero__note">
            <span class="material-symbols-outlined">trending_up</span>
            <div>
                <strong>{{ number_format($paketPerhatian) }} paket perlu perhatian</strong>
                <p>Gunakan filter analisis untuk masuk ke lokasi, jenis paket, dan daftar risiko.</p>
            </div>
        </div>
    </div>
</div>
