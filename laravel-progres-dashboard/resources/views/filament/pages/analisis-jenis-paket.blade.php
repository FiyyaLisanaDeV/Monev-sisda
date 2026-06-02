<x-filament-panels::page>
    <div class="dashboard-card mb-6">
        <span class="dashboard-card__eyebrow">Analisis Mendalam</span>
        <h2 class="dashboard-card__title">Distribusi Jenis Paket</h2>
        <p class="dashboard-card__meta">
            Perbandingan jumlah paket, pagu, realisasi, dan paket kritis untuk setiap jenis pengadaan.
        </p>
    </div>

    @if($jenis->isEmpty())
        <div class="dashboard-empty-state">
            <span class="material-symbols-outlined text-4xl mb-3">inbox</span>
            <strong>Tidak ada data jenis paket</strong>
            <p class="mt-2 text-center">Belum ada data yang bisa ditampilkan untuk analisis ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($jenis as $item)
                <div class="dashboard-card">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="dashboard-card__eyebrow">Jenis Paket</span>
                            <h3 class="dashboard-card__title text-2xl">
                                {{ $item->jenis_paket ?: 'Tidak Diketahui' }}
                            </h3>
                        </div>
                        <div class="px-3 py-1.5 rounded-full bg-[rgba(251,183,23,0.12)] text-[var(--dash-primary)] text-xs font-bold tracking-[0.08em] uppercase">
                            {{ number_format($item->jumlah_kritis) }} Kritis
                        </div>
                    </div>

                    <div class="dashboard-stat-line">
                        <span>Jumlah Paket</span>
                        <strong>{{ number_format($item->jumlah_paket) }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Total Pagu</span>
                        <strong>Rp {{ number_format($item->total_pagu, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Total Realisasi</span>
                        <strong>Rp {{ number_format($item->total_realisasi, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line dashboard-stat-line--accent">
                        <span>Serapan Total</span>
                        <strong>
                            {{ $item->total_pagu > 0 ? number_format($item->total_realisasi / $item->total_pagu * 100, 2) : 0 }}%
                        </strong>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
