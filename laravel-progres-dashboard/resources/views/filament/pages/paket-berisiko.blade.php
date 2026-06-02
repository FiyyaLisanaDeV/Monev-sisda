<x-filament-panels::page>
    <div class="dashboard-card mb-6">
        <span class="dashboard-card__eyebrow">Pantauan Eksekutif</span>
        <h2 class="dashboard-card__title">Daftar Paket Berisiko</h2>
        <p class="dashboard-card__meta">
            Fokus pada paket dengan status kritis dan perhatian, lengkap dengan filter, ekspor, dan detail per baris.
        </p>
        <div class="dashboard-chip-row mt-4">
            <div class="dashboard-chip dashboard-chip--accent">
                <span class="material-symbols-outlined">download</span>
                <span>Export tersedia</span>
            </div>
            <div class="dashboard-chip">
                <span class="material-symbols-outlined">search</span>
                <span>Filter cepat</span>
            </div>
            <div class="dashboard-chip">
                <span class="material-symbols-outlined">visibility</span>
                <span>Lihat detail paket</span>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
