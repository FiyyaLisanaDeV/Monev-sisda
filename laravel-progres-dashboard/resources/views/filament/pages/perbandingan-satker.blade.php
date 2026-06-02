<x-filament-panels::page>
    <div class="dashboard-card mb-6">
        <span class="dashboard-card__eyebrow">Analisis Mendalam</span>
        <h2 class="dashboard-card__title">Perbandingan Kinerja Satker</h2>
        <p class="dashboard-card__meta">
            Bandingkan pagu, realisasi, progres fisik, dan paket kritis per satker untuk melihat perbedaan kinerja dengan cepat.
        </p>
    </div>

    @if($satkers->isEmpty())
        <div class="dashboard-empty-state">
            <span class="material-symbols-outlined text-4xl mb-3">domain</span>
            <strong>Tidak ada data satker</strong>
            <p class="mt-2 text-center">Data satker belum tersedia untuk analisis perbandingan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($satkers as $satker)
                <div class="dashboard-card">
                    <span class="dashboard-card__eyebrow">Satker</span>
                    <h3 class="dashboard-card__title text-xl">{{ $satker->satker }}</h3>

                    <div class="dashboard-stat-line">
                        <span>Jumlah Paket</span>
                        <strong>{{ number_format($satker->jumlah_paket) }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Total Pagu</span>
                        <strong>Rp {{ number_format($satker->total_pagu, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Total Realisasi</span>
                        <strong>Rp {{ number_format($satker->total_realisasi, 0, ',', '.') }}</strong>
                    </div>
                    <div class="dashboard-stat-line">
                        <span>Rata-rata Fisik</span>
                        <strong>{{ number_format($satker->rata_rata_fisik, 2) }}%</strong>
                    </div>
                    <div class="dashboard-stat-line dashboard-stat-line--accent">
                        <span>Paket Kritis</span>
                        <strong>{{ number_format($satker->jumlah_kritis) }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
