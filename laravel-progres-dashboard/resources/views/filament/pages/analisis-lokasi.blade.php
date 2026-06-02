<x-filament-panels::page>
    <div class="dashboard-card mb-6">
        <span class="dashboard-card__eyebrow">Analisis Mendalam</span>
        <h2 class="dashboard-card__title">Analisis Berdasarkan Lokasi</h2>
        <p class="dashboard-card__meta">
            Pemetaan pagu, realisasi, dan risiko agar lokasi dengan beban tertinggi bisa diprioritaskan lebih cepat.
        </p>
    </div>

    @if($lokasis->isEmpty())
        <div class="dashboard-empty-state">
            <span class="material-symbols-outlined text-4xl mb-3">place</span>
            <strong>Tidak ada data lokasi</strong>
            <p class="mt-2 text-center">Belum ada lokasi yang bisa ditampilkan pada halaman analisis ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($lokasis as $lokasi)
                <div class="dashboard-card">
                    <span class="dashboard-card__eyebrow">Lokasi</span>
                    <h3 class="dashboard-card__title text-xl truncate" title="{{ $lokasi->lokasi }}">
                        {{ $lokasi->lokasi ?: 'Tanpa Lokasi' }}
                    </h3>

                    <div class="dashboard-stat-line">
                        <span>Total Pagu</span>
                        <strong>Rp {{ number_format($lokasi->total_pagu, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Realisasi</span>
                        <strong>Rp {{ number_format($lokasi->total_realisasi, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Paket</span>
                        <strong>{{ number_format($lokasi->jumlah_paket) }}</strong>
                    </div>
                    <div class="dashboard-stat-line dashboard-stat-line--accent">
                        <span>Kritis</span>
                        <strong>{{ number_format($lokasi->jumlah_kritis) }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
