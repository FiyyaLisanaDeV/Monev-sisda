# Laravel Dashboard Monitoring Progres TA 2025

Dashboard Executive berbasis Laravel dan Filament untuk memantau, memvalidasi, dan menganalisis Progres Fisik & Keuangan Tahun Anggaran 2025 secara otomatis dari file Excel.

Aplikasi ini berfokus pada **Akurasi Data, Jejak Audit (Audit Trail) yang Ketat, dan Pencegahan *Double Counting***.

## 🚀 Fitur Utama
1. **Automated Excel Processing**: Mengimpor multi-sheet Excel (OPSDA, BENDUNGAN, BALAI, PJSA, PJPA) via *Background Jobs* tanpa *blocking*.
2. **Immutable Audit Trail**: Seluruh baris data mentah dari Excel akan disimpan ke `raw_progres_rows`. Data import tidak dapat diubah (Edit/Delete) secara manual oleh *Admin* sekalipun.
3. **Smart Data Cleaning**:
   - Pembersihan otomatis angka desimal, titik ribuan, dan koma.
   - Deteksi pintar kolom berdasarkan variasi nama *(fuzzy matching)*.
4. **Anti-Double Counting**: Baris agregat seperti `TOTAL` dan `SUBTOTAL`, serta baris tanpa *Lokasi* tidak akan dimasukkan sebagai Paket.
5. **Auto Risk Scoring**: Perhitungan otomatis status proyek (Aman, Perlu Review, Perlu Perhatian, Kritis) berdasarkan deviasi (gap) persentase Keuangan & Fisik.
6. **Premium UI/UX**: *Glassmorphism Dashboard* dengan mode gelap, animasi mulus, dan tipografi kustom (Outfit).
7. **Role-Based Access Control (RBAC)**: Pembatasan ketat otoritas sistem (Admin vs Operator).

## 🛠 Stack Teknologi
- **Backend**: Laravel 11
- **Admin Panel**: Filament v3
- **Database**: SQLite (dapat diganti ke PostgreSQL/MySQL)
- **Excel Processor**: Maatwebsite Laravel Excel
- **Background Jobs**: Laravel Queue
- **Auth & Roles**: Spatie Laravel Permission
- **Aesthetic**: Custom TailwindCSS + Glassmorphism

## 📦 Cara Instalasi

1. **Clone & Install Dependencies**
   ```bash
   git clone <repository_url>
   cd laravel-progres-dashboard
   composer install
   npm install && npm run build
   ```

2. **Konfigurasi Environment**
   Salin `.env.example` menjadi `.env` lalu sesuaikan konfigurasi *Database* Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database & Seeding**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Perintah ini akan membuat Role dasar, Admin awal, dan mengatur permissions).*

4. **Menyalakan Worker Antrean (Wajib untuk Import)**
   Proses import Excel berjalan di latar belakang agar tidak *timeout*.
   ```bash
   php artisan queue:work
   ```

5. **Menjalankan Server**
   ```bash
   php artisan serve
   ```
   Akses di browser melalui: `http://localhost:8000/admin`
   **Login**: moleng (tanpa `@com`), **Password**: hangus

## 📊 Aturan Bisnis (Business Rules)

### 1. Standar Pembersihan (Cleaning Rules)
- Angka seperti `Rp 1.500.000,50` dibersihkan menjadi format desimal `1500000.50`.
- Teks kosong, `nan`, atau `-` dikonversi menjadi `null`.
- Kolom persentase seperti `75.5%` diubah menjadi `75.5`.

### 2. Deteksi Paket Detail (Anti Double-Counting)
Sistem **TIDAK** akan memproses baris sebagai paket detail jika:
- Nama paket mengandung kata `TOTAL` atau `SUBTOTAL`.
- Kolom *Lokasi* atau *Kode* kosong.

### 3. Perhitungan Skor Risiko (Risk Scoring)
Sistem menghitung risiko dengan prioritas berikut:
- **Kritis (Score 3)**: Jika realisasi nol, ATAU serapan keuangan < 70%, ATAU fisik < 70%.
- **Perlu Perhatian (Score 2)**: Jika serapan keuangan < 90% ATAU fisik < 90%.
- **Perlu Review (Score 1)**: Jika Gap (selisih) antara Fisik dan Keuangan > 15%.
- **Aman (Score 0)**: Jika tidak memenuhi semua kondisi di atas.

## 🔒 Tata Kelola Audit Data
- Tabel `raw_progres_rows` dan `paket_progres` tidak memiliki *Interface* Edit/Delete.
- Tabel `import_batches` dikunci agar tidak dapat dihapus setelah data masuk.
- Jika ada kesalahan import, Anda tidak bisa mengedit angkanya secara manual. **Solusi**: Lakukan rekap ulang di Excel dan lakukan unggah *Import Batch* baru!

## 🧪 Testing
Aplikasi telah dilengkapi dengan *Automated Testing* untuk menguji ketahanan logika *cleaning* dan *metrics*.
Jalankan pengujian menggunakan:
```bash
php artisan test
```

---
*Dikembangkan secara eksklusif untuk Monitoring Progres TA 2025.*
