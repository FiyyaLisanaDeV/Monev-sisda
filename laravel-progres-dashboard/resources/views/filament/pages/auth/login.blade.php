<x-filament-panels::page.simple>
    <div class="sisda-login-shell">
        <section class="sisda-login-hero" aria-label="Ringkasan aplikasi">
            <div class="sisda-login-brand">
                <div class="sisda-login-brand__mark">
                    <span class="material-symbols-outlined" aria-hidden="true">monitoring</span>
                </div>
                <div>
                    <p>SISDA Monev</p>
                    <span>Pantau Cepat, Kendali Tepat</span>
                </div>
            </div>

            <div class="sisda-login-copy">
                <span class="dashboard-eyebrow">Dashboard Eksekutif</span>
                <h1>Pantau progres fisik, keuangan, dan risiko paket dalam satu layar.</h1>
                <p>
                    Sistem internal untuk monitoring, validasi, dan analisis progres pelaksanaan.
                    Masuk diperlukan untuk melihat data operasional dan ringkasan kinerja.
                </p>
            </div>

            <div class="sisda-login-assurance">
                <div>
                    <span class="material-symbols-outlined" aria-hidden="true">verified_user</span>
                    <p>Akses terbatas</p>
                </div>
                <div>
                    <span class="material-symbols-outlined" aria-hidden="true">database</span>
                    <p>Data terlindungi</p>
                </div>
                <div>
                    <span class="material-symbols-outlined" aria-hidden="true">history</span>
                    <p>Jejak audit</p>
                </div>
            </div>
        </section>

        <section class="sisda-login-panel" aria-label="Form login">
            <div class="sisda-login-panel__header">
                <div class="sisda-login-panel__icon">
                    <span class="material-symbols-outlined" aria-hidden="true">lock_open</span>
                </div>
                <div>
                    <span>Akses Admin</span>
                    <h2>Masuk ke SISDA Monev</h2>
                    <p>Gunakan akun yang terdaftar untuk membuka dashboard monitoring.</p>
                </div>
            </div>

            {{ $this->content }}

            <div class="sisda-login-hint">
                <span class="material-symbols-outlined" aria-hidden="true">shield</span>
                <p>Data impor dan hasil pemantauan tersimpan dengan jejak audit.</p>
            </div>

            <footer class="sisda-login-footer">
                <span>Copyright SISDA 2016.</span>
                <a href="https://fiyya.cloud" target="_blank" rel="noopener noreferrer">{moleng was here}</a>
            </footer>
        </section>
    </div>
</x-filament-panels::page.simple>
