# Monev Sisda Dashboard

Repository ini berisi dashboard monitoring progres berbasis Laravel + Filament, beserta referensi desain visual dan dokumen narasi perbaikan UX.

## Isi Repo

- `laravel-progres-dashboard/` - aplikasi utama Laravel Filament
- `Dashboard Design/` - referensi visual HTML untuk acuan UI
- `dashboardproject.md` - ringkasan progres kerja terkini
- `narasi_kesalahan_visual_dashboard_mobile.md` - narasi masalah visual mobile yang menjadi acuan perbaikan

## Fokus Aplikasi

- Monitoring progres fisik dan keuangan
- Dashboard eksekutif dengan tampilan light mode
- Analisis paket, lokasi, dan satker
- Daftar paket berisiko dengan detail yang ramah mobile

## Status Terkini

- Dark mode sudah dinonaktifkan pada panel admin
- Dashboard eksekutif sudah diselaraskan dengan referensi visual
- Modal detail paket berisiko sudah dibuat lebih responsif untuk mobile
- Kolom `Status` pada tabel paket berisiko sudah dipindah ke urutan paling depan

## Menjalankan Aplikasi

Masuk ke folder aplikasi:

```bash
cd laravel-progres-dashboard
```

Lalu jalankan dependensi dan build bila diperlukan:

```bash
composer install
npm install
npm run build
```

Jalankan migrasi dan server lokal:

```bash
php artisan migrate
php artisan serve
```

Akses panel admin:

```text
/admin
```

## Catatan

- Aplikasi ini dikonfigurasi untuk light mode.
- Detail teknis perubahan UI dan progres kerja bisa dilihat di `dashboardproject.md`.
