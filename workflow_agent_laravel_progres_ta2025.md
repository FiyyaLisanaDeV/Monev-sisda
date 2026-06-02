# Workflow Eksekusi Agent — Laravel Dashboard Monitoring Progres TA 2025

## 0. Tujuan

Bangun aplikasi **Laravel Dashboard Monitoring Progres TA 2025** berdasarkan file Excel:

```text
Progres 31 Desember 2025.xlsx
```

Aplikasi ini dipakai untuk:

- upload file Excel progres TA 2025;
- membaca beberapa sheet/satker;
- menyimpan baris mentah sebagai arsip;
- membersihkan dan menormalkan data;
- memisahkan baris paket detail dari baris agregat;
- menghitung pagu, realisasi, sisa anggaran, progres fisik, progres keuangan;
- membuat risk scoring paket;
- menampilkan dashboard executive;
- menyediakan filter, tabel detail, dan export data;
- menjaga audit trail import agar data bisa ditelusuri ulang.

Fokus MVP bukan tampilan ramai. Fokus utama adalah **akurasi data, validasi cleaning, dan pencegahan double counting**.

---

# 1. Prinsip Utama

Agent wajib mengikuti prinsip berikut:

```text
1. Jangan mengubah file Excel asli.
2. Jangan overwrite file sumber.
3. Simpan file upload sebagai arsip.
4. Simpan semua baris Excel mentah ke tabel raw.
5. Jangan menghitung KPI dari raw rows.
6. Jangan menjumlahkan semua baris Excel mentah.
7. Jangan menghitung baris kegiatan/KRO/RO/subtotal/total sebagai paket.
8. KPI utama hanya dihitung dari tabel paket_progres.
9. Baris paket detail wajib memiliki kode, paket, dan lokasi.
10. Jangan hardcode hasil KPI.
11. Jangan menambahkan AI/LLM pada MVP.
12. Jangan membuat fitur terlalu luas sebelum import dan cleaning stabil.
13. Semua proses import harus bisa diaudit ulang.
14. Semua error import harus tercatat.
15. Dashboard tidak boleh tampil jika import batch belum valid.
```

---

# 2. Stack Rekomendasi

Gunakan stack berikut:

```text
Backend        : Laravel
Database       : PostgreSQL atau MySQL
Admin UI       : Filament
Excel Import   : Maatwebsite Laravel Excel
Chart          : ApexCharts / ECharts / Chart.js
Permission     : Spatie Laravel Permission
Queue          : Laravel Queue
Storage        : Laravel Storage
Testing        : PHPUnit / Pest
```

Untuk MVP tercepat:

```text
Laravel + Filament + Laravel Excel + PostgreSQL/MySQL
```

Catatan:

```text
1. Gunakan Filament agar dashboard, tabel, form upload, dan filter cepat dibuat.
2. Gunakan database sejak awal karena Laravel cocok untuk aplikasi production.
3. Jangan tambahkan API eksternal sebelum dashboard stabil.
4. Jangan tambahkan AI.
5. Jangan tambahkan multi-tenant sebelum struktur data stabil.
```

---

# 3. Arsitektur Data

Alur data wajib:

```text
Excel File
   ↓
Upload Progres
   ↓
Import Batch
   ↓
Raw Excel Rows
   ↓
Header Detection
   ↓
Column Normalization
   ↓
Data Cleaning
   ↓
Paket Detail Detection
   ↓
Metric Calculation
   ↓
Risk Scoring
   ↓
Paket Progres Table
   ↓
Dashboard / Filter / Export
```

Pemisahan tabel:

```text
import_batches       = metadata upload/import
raw_progres_rows     = semua baris Excel mentah
paket_progres        = hanya baris paket detail yang sudah bersih
data_quality_reports = laporan validasi data per batch
```

---

# 4. Sheet Excel yang Dibaca

Sheet utama:

```php
const SHEET_NAMES = [
    'OPSDA',
    'BENDUNGAN',
    'BALAI',
    'PJSA',
    'PJPA',
];
```

Setiap sheet dianggap sebagai satker.

Ketika data disimpan, wajib tambahkan:

```text
satker
sheet_name
row_number
import_batch_id
```

---

# 5. Struktur Project Laravel

Buat struktur utama seperti berikut:

```text
laravel-progres-dashboard/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── ImportBatchResource.php
│   │   │   └── PaketProgresResource.php
│   │   └── Pages/
│   │       ├── ExecutiveDashboard.php
│   │       ├── PerbandinganSatker.php
│   │       ├── PaketBerisiko.php
│   │       ├── AnalisisLokasi.php
│   │       ├── AnalisisJenisPaket.php
│   │       └── DataQualityDashboard.php
│   ├── Imports/
│   │   └── ProgresExcelImport.php
│   ├── Jobs/
│   │   └── ProcessProgresImportJob.php
│   ├── Models/
│   │   ├── ImportBatch.php
│   │   ├── RawProgresRow.php
│   │   ├── PaketProgres.php
│   │   └── DataQualityReport.php
│   ├── Services/
│   │   └── Progres/
│   │       ├── ExcelSheetReader.php
│   │       ├── HeaderDetector.php
│   │       ├── ColumnNormalizer.php
│   │       ├── DataCleaner.php
│   │       ├── PaketDetailDetector.php
│   │       ├── MetricCalculator.php
│   │       ├── RiskScoringService.php
│   │       ├── DataQualityService.php
│   │       └── DashboardQueryService.php
│   └── Support/
│       └── Formatters/
│           ├── RupiahFormatter.php
│           └── PercentFormatter.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── storage/
│   └── app/
│       └── progres-imports/
├── tests/
│   ├── Feature/
│   └── Unit/
└── README.md
```

---

# 6. Database Schema

## 6.1. `import_batches`

Tabel untuk metadata upload/import.

```text
id
file_name
file_path
periode
tahun_anggaran
uploaded_by
status
total_sheets
total_raw_rows
total_paket_detail
total_error_rows
started_at
finished_at
error_message
created_at
updated_at
```

Status:

```text
uploaded
processing
completed
completed_with_warning
failed
```

---

## 6.2. `raw_progres_rows`

Tabel untuk menyimpan semua baris Excel mentah.

```text
id
import_batch_id
satker
sheet_name
row_number
raw_data_json
detected_header_json
created_at
updated_at
```

Catatan:

```text
1. raw_data_json wajib menyimpan isi baris apa adanya.
2. Jangan melakukan kalkulasi KPI dari tabel ini.
3. Tabel ini dipakai untuk audit dan debugging cleaning.
```

---

## 6.3. `paket_progres`

Tabel utama dashboard. Hanya berisi baris paket detail.

```text
id
import_batch_id
satker
sheet_name
row_number

kode
paket
lokasi
jenis_paket
metode_pemilihan
sumber_dana

pagu
realisasi
pagu_setelah_efisiensi
blokir

keuangan_percent
fisik_percent
keuangan_setelah_efisiensi_percent
fisik_setelah_efisiensi_percent

sisa_anggaran
serapan_terhadap_pagu
serapan_terhadap_pagu_efisiensi
gap_fisik_keuangan
gap_keuangan_fisik

status_risiko
risk_score

raw_payload_json
cleaning_notes_json

created_at
updated_at
```

Index yang disarankan:

```text
import_batch_id
satker
lokasi
jenis_paket
status_risiko
risk_score
kode
```

---

## 6.4. `data_quality_reports`

Tabel laporan kualitas data per import batch.

```text
id
import_batch_id

total_raw_rows
total_paket_detail
baris_tanpa_kode
baris_tanpa_lokasi
baris_tanpa_pagu
baris_realisasi_lebih_besar_dari_pagu
baris_pagu_nol_realisasi_ada
baris_keuangan_kosong
baris_fisik_kosong
jumlah_paket_per_satker_json
warning_json
created_at
updated_at
```

---

# 7. Model dan Relasi

## `ImportBatch`

Relasi:

```php
public function rawRows()
{
    return $this->hasMany(RawProgresRow::class);
}

public function paketProgres()
{
    return $this->hasMany(PaketProgres::class);
}

public function dataQualityReport()
{
    return $this->hasOne(DataQualityReport::class);
}
```

---

# 8. Aturan Deteksi Header

Buat service:

```text
app/Services/Progres/HeaderDetector.php
```

Tugas:

```text
1. Membaca beberapa baris awal setiap sheet.
2. Mencari baris yang paling mungkin menjadi header.
3. Header dianggap valid jika mengandung beberapa keyword penting.
```

Keyword:

```text
Kode
Paket
Kegiatan
Lokasi
Pagu
Realisasi
Keuangan
Keu
Fisik
Fis
```

Aturan:

```text
1. Jangan anggap baris pertama pasti header.
2. Header bisa berada setelah judul laporan.
3. Jika header tidak ditemukan, tandai sheet sebagai error.
4. Jangan lanjut cleaning untuk sheet yang header-nya gagal ditemukan.
```

Acceptance criteria:

```text
- Header sheet OPSDA terdeteksi.
- Header sheet BENDUNGAN terdeteksi.
- Header sheet BALAI terdeteksi.
- Header sheet PJSA terdeteksi.
- Header sheet PJPA terdeteksi.
- Jika header gagal, error tercatat di import batch.
```

---

# 9. Aturan Normalisasi Kolom

Buat service:

```text
app/Services/Progres/ColumnNormalizer.php
```

Standarkan kolom menjadi:

```text
satker
kode
paket
lokasi
jenis_paket
metode_pemilihan
sumber_dana
pagu
realisasi
keuangan_percent
fisik_percent
pagu_setelah_efisiensi
keuangan_setelah_efisiensi_percent
fisik_setelah_efisiensi_percent
blokir
```

Mapping toleran:

```text
Kode                         -> kode
Paket                        -> paket
Nama Paket                   -> paket
Kegiatan                     -> paket
Lokasi                       -> lokasi
Jenis Paket                  -> jenis_paket
Metode                       -> metode_pemilihan
Metode Pemilihan             -> metode_pemilihan
Sumber Dana                  -> sumber_dana
Pagu                         -> pagu
Realisasi                    -> realisasi
Keu                          -> keuangan_percent
Keuangan                     -> keuangan_percent
Fis                          -> fisik_percent
Fisik                        -> fisik_percent
Pagu Setelah Efisiensi       -> pagu_setelah_efisiensi
Keu Setelah Efisiensi        -> keuangan_setelah_efisiensi_percent
Keuangan Setelah Efisiensi   -> keuangan_setelah_efisiensi_percent
Fis Setelah Efisiensi        -> fisik_setelah_efisiensi_percent
Fisik Setelah Efisiensi      -> fisik_setelah_efisiensi_percent
Blokir                       -> blokir
```

Acceptance criteria:

```text
- Semua kolom wajib tersedia walaupun nilainya null.
- Variasi nama kolom tetap bisa dipetakan.
- Kolom yang tidak dikenal disimpan di raw_payload_json.
```

---

# 10. Aturan Cleaning Data

Buat service:

```text
app/Services/Progres/DataCleaner.php
```

Aturan cleaning:

```text
1. Trim semua string.
2. Ubah "nan", "-", "", "null", "NULL", None menjadi null.
3. Bersihkan angka rupiah dari simbol, titik ribuan, koma desimal.
4. Bersihkan persen dari simbol "%".
5. Konversi kolom uang menjadi numeric/decimal.
6. Konversi kolom persen menjadi numeric/decimal.
7. Buang baris kosong penuh dari proses paket detail.
8. Jangan hapus raw rows dari tabel raw_progres_rows.
9. Catat cleaning_notes_json jika ada nilai tidak normal.
```

Kolom uang:

```text
pagu
realisasi
pagu_setelah_efisiensi
blokir
```

Kolom persen:

```text
keuangan_percent
fisik_percent
keuangan_setelah_efisiensi_percent
fisik_setelah_efisiensi_percent
```

Acceptance criteria:

```text
- Nilai uang bisa dijumlahkan.
- Nilai persen bisa dibandingkan.
- Tidak ada error jika nilai kosong.
- Tidak ada nilai inf, -inf, NaN pada hasil kalkulasi.
```

---

# 11. Aturan Deteksi Paket Detail

Buat service:

```text
app/Services/Progres/PaketDetailDetector.php
```

Baris dianggap paket detail jika:

```text
kode tidak kosong
paket tidak kosong
lokasi tidak kosong
bukan total
bukan subtotal
bukan baris kegiatan agregat
bukan baris KRO/RO agregat
```

Rule awal:

```php
$isPaketDetail =
    !empty($row['kode']) &&
    !empty($row['paket']) &&
    !empty($row['lokasi']) &&
    !str_contains(strtoupper($row['paket']), 'TOTAL') &&
    !str_contains(strtoupper($row['paket']), 'SUBTOTAL');
```

Tambahan filter agregat:

```text
1. Jika paket berisi "TOTAL", bukan paket.
2. Jika paket berisi "SUBTOTAL", bukan paket.
3. Jika lokasi kosong, bukan paket.
4. Jika kode kosong, bukan paket.
5. Jika pagu kosong dan realisasi kosong, perlu review.
```

Acceptance criteria:

```text
- Baris kegiatan tidak masuk paket_progres.
- Baris KRO/RO tidak masuk paket_progres.
- Baris subtotal tidak masuk paket_progres.
- Baris total tidak masuk paket_progres.
- Semua KPI dashboard membaca paket_progres, bukan raw_progres_rows.
```

---

# 12. Metric Calculation

Buat service:

```text
app/Services/Progres/MetricCalculator.php
```

Kolom turunan:

```text
sisa_anggaran
serapan_terhadap_pagu
serapan_terhadap_pagu_efisiensi
gap_fisik_keuangan
gap_keuangan_fisik
```

Formula:

```text
sisa_anggaran = pagu_setelah_efisiensi - realisasi

serapan_terhadap_pagu =
    realisasi / pagu * 100

serapan_terhadap_pagu_efisiensi =
    realisasi / pagu_setelah_efisiensi * 100

gap_fisik_keuangan =
    fisik_setelah_efisiensi_percent - keuangan_setelah_efisiensi_percent

gap_keuangan_fisik =
    keuangan_setelah_efisiensi_percent - fisik_setelah_efisiensi_percent
```

Aturan pembagian:

```text
Jika pagu = 0 atau null, serapan_terhadap_pagu = 0.
Jika pagu_setelah_efisiensi = 0 atau null, serapan_terhadap_pagu_efisiensi = 0.
Tidak boleh menghasilkan inf, -inf, atau error.
```

Acceptance criteria:

```text
- Semua paket memiliki sisa_anggaran.
- Semua paket memiliki serapan_terhadap_pagu.
- Semua paket memiliki serapan_terhadap_pagu_efisiensi.
- Semua paket memiliki gap_fisik_keuangan.
- Semua paket memiliki gap_keuangan_fisik.
```

---

# 13. Risk Scoring

Buat service:

```text
app/Services/Progres/RiskScoringService.php
```

Aturan:

```php
public function calculate(array $row): array
{
    $realisasi = $row['realisasi'] ?? 0;
    $keu = $row['keuangan_setelah_efisiensi_percent'] ?? 0;
    $fisik = $row['fisik_setelah_efisiensi_percent'] ?? 0;
    $gap = abs($row['gap_fisik_keuangan'] ?? 0);

    if ($realisasi == 0) {
        return ['status_risiko' => 'Kritis', 'risk_score' => 3];
    }

    if ($keu < 70 || $fisik < 70) {
        return ['status_risiko' => 'Kritis', 'risk_score' => 3];
    }

    if ($keu < 90 || $fisik < 90) {
        return ['status_risiko' => 'Perlu Perhatian', 'risk_score' => 2];
    }

    if ($gap > 15) {
        return ['status_risiko' => 'Perlu Review', 'risk_score' => 1];
    }

    return ['status_risiko' => 'Aman', 'risk_score' => 0];
}
```

Urutan paket berisiko:

```text
risk_score desc
sisa_anggaran desc
pagu_setelah_efisiensi desc
```

Acceptance criteria:

```text
- Paket Kritis muncul.
- Paket Perlu Perhatian muncul.
- Paket Perlu Review muncul jika gap > 15.
- Paket Aman muncul jika tidak memenuhi kondisi risiko.
```

---

# 14. Dashboard Query Service

Buat service:

```text
app/Services/Progres/DashboardQueryService.php
```

Semua query dashboard harus menerima:

```text
import_batch_id
filter satker
filter lokasi
filter jenis_paket
filter status_risiko
search
```

Query utama:

```text
getExecutiveKpis()
getPaguRealisasiBySatker()
getSerapanBySatker()
getRiskDistribution()
getTopRiskPackages()
getComparisonBySatker()
getLocationRanking()
getPackageTypeAnalysis()
getDetailPackages()
```

Aturan:

```text
1. Semua query membaca paket_progres.
2. Jangan membaca raw_progres_rows untuk KPI.
3. Gunakan import_batch_id agar data antar periode tidak tercampur.
4. Default dashboard membaca import batch terakhir yang statusnya completed.
```

---

# 15. Halaman Aplikasi

## 15.1. Upload / Import Batch

Halaman:

```text
/admin/import-batches
```

Fitur:

```text
1. Upload Excel.
2. Input periode.
3. Input tahun anggaran.
4. Lihat status import.
5. Lihat total raw rows.
6. Lihat total paket detail.
7. Lihat error/warning.
8. Tombol proses ulang import.
```

Acceptance criteria:

```text
- File Excel bisa diupload.
- File tersimpan di storage.
- Import batch tercatat.
- Job import berjalan.
- Status berubah dari uploaded → processing → completed/failed.
```

---

## 15.2. Executive Dashboard

Halaman:

```text
/admin/executive-dashboard
```

Isi KPI:

```text
Total Pagu
Total Pagu Setelah Efisiensi
Total Realisasi
Total Sisa Anggaran
Serapan terhadap Pagu
Serapan terhadap Pagu Efisiensi
Rata-rata Fisik
Total Paket
Paket Kritis
Paket Perlu Perhatian
```

Chart:

```text
Pagu vs Realisasi per Satker
Serapan per Satker
Distribusi Status Risiko
Top 20 Paket Paling Berisiko
```

Acceptance criteria:

```text
- KPI tidak kosong.
- KPI berasal dari paket_progres.
- Chart tampil.
- Top 20 paket risiko tampil.
```

---

## 15.3. Perbandingan Satker

Halaman:

```text
/admin/perbandingan-satker
```

Isi:

```text
satker
jumlah_paket
total_pagu
total_realisasi
total_sisa_anggaran
serapan_percent
rata_rata_fisik_percent
jumlah_paket_kritis
```

Chart:

```text
Pagu per Satker
Realisasi per Satker
Sisa Anggaran per Satker
Paket Kritis per Satker
```

Acceptance criteria:

```text
- Agregasi per satker benar.
- Tidak double counting.
- Semua angka berasal dari paket_progres.
```

---

## 15.4. Paket Berisiko

Halaman:

```text
/admin/paket-berisiko
```

Filter:

```text
Satker
Lokasi
Jenis Paket
Status Risiko
```

Tabel:

```text
satker
kode
paket
lokasi
jenis_paket
pagu_setelah_efisiensi
realisasi
sisa_anggaran
keuangan_setelah_efisiensi_percent
fisik_setelah_efisiensi_percent
gap_fisik_keuangan
status_risiko
```

Sort default:

```text
risk_score desc
sisa_anggaran desc
pagu_setelah_efisiensi desc
```

Acceptance criteria:

```text
- Filter bekerja.
- Sort risiko bekerja.
- Export CSV/XLSX sesuai filter.
```

---

## 15.5. Analisis Lokasi

Halaman:

```text
/admin/analisis-lokasi
```

Isi:

```text
Ranking lokasi berdasarkan total pagu
Ranking lokasi berdasarkan realisasi
Ranking lokasi berdasarkan jumlah paket
Ranking lokasi berdasarkan jumlah paket kritis
Chart pagu per lokasi
Chart jumlah paket per lokasi
```

Catatan:

```text
Peta tidak wajib untuk MVP.
Peta dibuat setelah lokasi distandarkan.
```

---

## 15.6. Analisis Jenis Paket

Halaman:

```text
/admin/analisis-jenis-paket
```

Isi:

```text
Distribusi jumlah paket berdasarkan jenis paket
Total pagu berdasarkan jenis paket
Total realisasi berdasarkan jenis paket
Serapan berdasarkan jenis paket
Jumlah paket kritis berdasarkan jenis paket
```

---

## 15.7. Detail Paket

Halaman:

```text
/admin/detail-paket
```

Filter:

```text
Satker
Lokasi
Jenis Paket
Metode Pemilihan
Sumber Dana
Status Risiko
Search nama paket/kode
```

Tabel:

```text
satker
kode
paket
lokasi
jenis_paket
metode_pemilihan
sumber_dana
pagu
realisasi
pagu_setelah_efisiensi
sisa_anggaran
keuangan_setelah_efisiensi_percent
fisik_setelah_efisiensi_percent
gap_fisik_keuangan
status_risiko
```

Fitur:

```text
search text
filter multi-select
download CSV
download XLSX
```

---

## 15.8. Data Quality Dashboard

Halaman:

```text
/admin/data-quality
```

Isi:

```text
Total baris raw
Total paket detail
Baris tanpa kode
Baris tanpa lokasi
Baris tanpa pagu
Realisasi lebih besar dari pagu
Pagu nol tapi realisasi ada
Keuangan kosong
Fisik kosong
Jumlah paket per satker
Warning import
```

Acceptance criteria:

```text
- Admin bisa melihat kualitas data sebelum mengambil kesimpulan dashboard.
- Warning tidak disembunyikan.
- Jika kualitas data buruk, tampilkan peringatan di executive dashboard.
```

---

# 16. Workflow Development Agent

## Phase 1 — Setup Project

Target:

```text
- Project Laravel berjalan.
- Filament terpasang.
- Database terkoneksi.
- Login admin bisa dibuka.
```

Pekerjaan:

```text
1. Buat project Laravel.
2. Konfigurasi .env database.
3. Install Filament.
4. Install Laravel Excel.
5. Install permission package jika langsung dibutuhkan.
6. Buat user admin awal.
```

Acceptance criteria:

```text
php artisan serve
php artisan migrate
/admin bisa dibuka
login admin berhasil
```

---

## Phase 2 — Migration dan Model

Target:

```text
- Tabel utama tersedia.
- Model dan relasi tersedia.
```

Pekerjaan:

```text
1. Buat migration import_batches.
2. Buat migration raw_progres_rows.
3. Buat migration paket_progres.
4. Buat migration data_quality_reports.
5. Buat model masing-masing tabel.
6. Tambahkan fillable/casts.
7. Tambahkan relasi model.
```

Acceptance criteria:

```text
php artisan migrate:fresh
semua tabel tersedia
relasi ImportBatch → RawRows berjalan
relasi ImportBatch → PaketProgres berjalan
relasi ImportBatch → DataQualityReport berjalan
```

---

## Phase 3 — Upload Excel

Target:

```text
- File Excel bisa diupload.
- Import batch tercatat.
- File tersimpan di storage.
```

Pekerjaan:

```text
1. Buat Filament Resource ImportBatch.
2. Tambahkan form upload file.
3. Simpan file ke storage/app/progres-imports.
4. Simpan metadata ke import_batches.
5. Set status awal uploaded.
```

Acceptance criteria:

```text
admin bisa upload file
file tidak overwrite file asli
record import batch tercipta
status = uploaded
```

---

## Phase 4 — Job Import

Target:

```text
- Import diproses via job.
- Status import berubah otomatis.
```

Pekerjaan:

```text
1. Buat ProcessProgresImportJob.
2. Job menerima import_batch_id.
3. Status berubah menjadi processing saat mulai.
4. Status menjadi completed jika berhasil.
5. Status menjadi failed jika error.
6. Error message tersimpan.
```

Acceptance criteria:

```text
php artisan queue:work
job import berjalan
status import tercatat
error tercatat jika gagal
```

---

## Phase 5 — Excel Reader dan Header Detector

Target:

```text
- Semua sheet utama terbaca.
- Header setiap sheet terdeteksi.
```

Pekerjaan:

```text
1. Buat ExcelSheetReader.
2. Baca sheet OPSDA, BENDUNGAN, BALAI, PJSA, PJPA.
3. Buat HeaderDetector.
4. Deteksi header berdasarkan keyword.
5. Catat sheet yang gagal.
```

Acceptance criteria:

```text
semua sheet terbaca
header tidak wajib berada di baris pertama
sheet gagal terbaca tercatat sebagai warning/error
```

---

## Phase 6 — Raw Rows Storage

Target:

```text
- Semua baris mentah tersimpan.
```

Pekerjaan:

```text
1. Loop setiap sheet.
2. Simpan row_number.
3. Simpan satker.
4. Simpan sheet_name.
5. Simpan raw_data_json.
```

Acceptance criteria:

```text
raw_progres_rows berisi data
total_raw_rows di import_batches terisi
tidak ada raw data yang dihitung sebagai KPI
```

---

## Phase 7 — Column Normalizer dan Data Cleaner

Target:

```text
- Kolom distandarkan.
- Nilai uang dan persen bersih.
```

Pekerjaan:

```text
1. Buat ColumnNormalizer.
2. Mapping nama kolom variasi ke kolom standar.
3. Buat DataCleaner.
4. Bersihkan string.
5. Konversi uang.
6. Konversi persen.
7. Tangani nilai kosong.
```

Acceptance criteria:

```text
kolom wajib tersedia
nilai uang numeric
nilai persen numeric
tidak error saat null
```

---

## Phase 8 — Paket Detail Detector

Target:

```text
- Baris paket detail terdeteksi.
- Baris agregat tidak masuk tabel paket_progres.
```

Pekerjaan:

```text
1. Buat PaketDetailDetector.
2. Cek kode, paket, lokasi.
3. Exclude TOTAL/SUBTOTAL.
4. Exclude baris agregat.
5. Simpan hanya paket detail ke paket_progres.
```

Acceptance criteria:

```text
paket_progres tidak kosong
baris total tidak masuk
baris subtotal tidak masuk
baris tanpa lokasi tidak masuk
```

---

## Phase 9 — Metrics dan Risk Scoring

Target:

```text
- Kolom turunan dan status risiko tersedia.
```

Pekerjaan:

```text
1. Buat MetricCalculator.
2. Hitung sisa anggaran.
3. Hitung serapan.
4. Hitung gap fisik-keuangan.
5. Buat RiskScoringService.
6. Hitung status_risiko.
7. Hitung risk_score.
```

Acceptance criteria:

```text
sisa_anggaran terisi
serapan_terhadap_pagu terisi
serapan_terhadap_pagu_efisiensi terisi
gap_fisik_keuangan terisi
status_risiko terisi
risk_score terisi
```

---

## Phase 10 — Data Quality Report

Target:

```text
- Laporan kualitas data tercatat.
```

Pekerjaan:

```text
1. Buat DataQualityService.
2. Hitung total raw rows.
3. Hitung total paket detail.
4. Hitung baris tanpa kode.
5. Hitung baris tanpa lokasi.
6. Hitung baris tanpa pagu.
7. Hitung realisasi > pagu.
8. Hitung pagu nol tapi realisasi ada.
9. Hitung keuangan kosong.
10. Hitung fisik kosong.
11. Simpan ke data_quality_reports.
```

Acceptance criteria:

```text
data_quality_reports tercipta setiap selesai import
warning tampil di halaman admin
```

---

## Phase 11 — Executive Dashboard MVP

Target:

```text
- Dashboard executive tampil.
- KPI utama tampil.
- Chart utama tampil.
```

Pekerjaan:

```text
1. Buat ExecutiveDashboard Filament Page.
2. Buat DashboardQueryService.
3. Tampilkan KPI cards.
4. Tampilkan chart pagu vs realisasi per satker.
5. Tampilkan chart serapan per satker.
6. Tampilkan distribusi risiko.
7. Tampilkan top 20 paket risiko.
```

Acceptance criteria:

```text
dashboard bisa dibuka
KPI tidak kosong
KPI berasal dari paket_progres
chart tampil
top risiko tampil
tidak double counting
```

---

## Phase 12 — Detail Paket dan Paket Berisiko

Target:

```text
- Tabel paket bisa difilter dan diexport.
```

Pekerjaan:

```text
1. Buat PaketProgresResource.
2. Tambahkan filter satker.
3. Tambahkan filter lokasi.
4. Tambahkan filter jenis paket.
5. Tambahkan filter status risiko.
6. Tambahkan search kode/paket.
7. Tambahkan export CSV/XLSX.
8. Buat PaketBerisiko page.
```

Acceptance criteria:

```text
filter bekerja
search bekerja
export sesuai filter
sort risiko bekerja
```

---

## Phase 13 — Analisis Lanjutan

Target:

```text
- Halaman satker, lokasi, dan jenis paket selesai.
```

Pekerjaan:

```text
1. Buat PerbandinganSatker page.
2. Buat AnalisisLokasi page.
3. Buat AnalisisJenisPaket page.
4. Gunakan DashboardQueryService.
5. Pastikan semua query membaca paket_progres.
```

Acceptance criteria:

```text
semua halaman bisa dibuka
semua chart tampil
agregasi tidak double counting
```

---

## Phase 14 — Role dan Audit Minimal

Target:

```text
- Akses admin/operator/pimpinan dipisahkan.
```

Role minimal:

```text
admin
operator
pimpinan
```

Hak akses:

```text
admin     = semua fitur
operator  = upload/import dan lihat data
pimpinan  = lihat dashboard dan export
```

Catatan:

```text
Jika MVP belum stabil, role boleh ditunda.
Namun struktur permission jangan sampai menghambat pengembangan.
```

Acceptance criteria:

```text
role admin bisa semua
role operator tidak bisa hapus data batch sembarangan
role pimpinan tidak bisa upload/import
```

---

## Phase 15 — Testing

Target:

```text
- Service utama memiliki test.
```

Unit test wajib:

```text
HeaderDetectorTest
ColumnNormalizerTest
DataCleanerTest
PaketDetailDetectorTest
MetricCalculatorTest
RiskScoringServiceTest
DataQualityServiceTest
```

Feature test:

```text
ImportBatchUploadTest
ProcessProgresImportJobTest
ExecutiveDashboardQueryTest
PaketProgresFilterTest
```

Acceptance criteria:

```text
php artisan test
test utama pass
```

---

## Phase 16 — Dokumentasi

Target:

```text
README.md selesai.
```

Isi README:

```text
1. Deskripsi aplikasi.
2. Stack teknologi.
3. Cara install.
4. Cara konfigurasi .env.
5. Cara migrate database.
6. Cara membuat admin.
7. Cara menjalankan queue.
8. Cara upload Excel.
9. Struktur sheet Excel.
10. Aturan cleaning.
11. Aturan paket detail.
12. Aturan risk scoring.
13. Catatan anti double counting.
14. Cara export data.
15. Roadmap pengembangan.
```

Acceptance criteria:

```text
developer baru bisa menjalankan project dari README
```

---

# 17. Definition of Done MVP

Project dianggap selesai untuk MVP jika:

```text
1. Laravel berjalan tanpa error.
2. Admin panel bisa login.
3. Excel bisa diupload.
4. File upload tidak merusak file asli.
5. Semua sheet utama terbaca.
6. Raw rows tersimpan.
7. Paket detail berhasil dipisahkan.
8. Baris total/subtotal/agregat tidak masuk KPI.
9. Metrics berhasil dihitung.
10. Risk scoring berhasil dihitung.
11. Executive dashboard tampil.
12. Perbandingan satker tampil.
13. Paket berisiko tampil.
14. Detail paket bisa difilter.
15. Export CSV/XLSX berjalan.
16. Data quality report tampil.
17. KPI tidak double counting.
18. README tersedia.
19. Test utama berjalan.
```

---

# 18. Roadmap Setelah MVP

Setelah MVP stabil, baru tambahkan:

```text
1. Upload progres multi-periode.
2. Perbandingan antar periode.
3. Trend bulanan.
4. Export PDF executive summary.
5. Peta kabupaten/kota.
6. Normalisasi lokasi.
7. Audit log detail perubahan.
8. Notifikasi paket kritis.
9. API read-only.
10. Docker deployment.
11. Backup database otomatis.
12. Object storage untuk file Excel.
13. Role lebih detail per satker.
14. Approval data sebelum tampil ke pimpinan.
```

Jangan menambahkan fitur roadmap sebelum MVP import-clean-dashboard stabil.

---

# 19. Prompt Eksekusi untuk Coding Agent

Gunakan prompt ini untuk agent coding:

```markdown
Anda adalah Senior Laravel Engineer, Data Engineer, dan Dashboard Developer.

Bangun aplikasi Laravel Dashboard Monitoring Progres TA 2025 berdasarkan file Excel `Progres 31 Desember 2025.xlsx`.

Target aplikasi:
- upload Excel progres;
- baca sheet OPSDA, BENDUNGAN, BALAI, PJSA, PJPA;
- simpan semua baris mentah ke raw_progres_rows;
- deteksi header secara defensif;
- normalisasi kolom;
- cleaning angka rupiah dan persen;
- deteksi baris paket detail;
- simpan hanya paket detail ke paket_progres;
- hitung metrics;
- hitung risk scoring;
- tampilkan dashboard executive;
- sediakan filter, detail paket, paket berisiko, data quality report, dan export CSV/XLSX.

Aturan utama:
1. Jangan mengubah file Excel asli.
2. Jangan overwrite file sumber.
3. Jangan hitung KPI dari raw_progres_rows.
4. KPI hanya boleh dari paket_progres.
5. Baris paket detail wajib memiliki kode, paket, dan lokasi.
6. Baris total/subtotal/agregat tidak boleh masuk paket_progres.
7. Jangan hardcode KPI.
8. Jangan tambahkan AI/LLM.
9. Jangan membuat fitur lanjutan sebelum import dan cleaning stabil.
10. Gunakan service class modular.
11. Gunakan job untuk proses import.
12. Simpan error dan warning import.
13. Buat README dan test minimal.

Stack:
- Laravel
- Filament
- Laravel Excel
- PostgreSQL atau MySQL
- Chart library yang kompatibel dengan Filament
- PHPUnit/Pest

Urutan kerja:
1. Setup Laravel + Filament.
2. Buat migration dan model.
3. Buat upload import batch.
4. Buat job import.
5. Buat ExcelSheetReader.
6. Buat HeaderDetector.
7. Simpan raw rows.
8. Buat ColumnNormalizer.
9. Buat DataCleaner.
10. Buat PaketDetailDetector.
11. Buat MetricCalculator.
12. Buat RiskScoringService.
13. Buat DataQualityService.
14. Buat DashboardQueryService.
15. Buat ExecutiveDashboard.
16. Buat PaketProgresResource.
17. Buat PaketBerisiko page.
18. Buat PerbandinganSatker page.
19. Buat AnalisisLokasi page.
20. Buat AnalisisJenisPaket page.
21. Tambahkan export CSV/XLSX.
22. Buat test.
23. Buat README.

Acceptance akhir:
- `php artisan migrate` berhasil.
- Admin panel bisa login.
- Upload Excel berhasil.
- Job import berhasil.
- Semua sheet utama terbaca.
- Raw rows tersimpan.
- Paket detail tersimpan.
- KPI tidak double counting.
- Dashboard tampil.
- Filter bekerja.
- Export bekerja.
- Data quality report tampil.
- `php artisan test` berjalan untuk test utama.
```

---

# 20. Prompt QA untuk Agent Reviewer

Gunakan prompt ini setelah coding selesai:

```markdown
Anda adalah Senior QA Engineer dan Data Auditor untuk aplikasi Laravel dashboard data pemerintah.

Review project ini dengan fokus pada akurasi data, import Excel, dan risiko double counting.

Cek hal berikut:

1. Apakah file Excel asli tidak berubah?
2. Apakah file upload disimpan sebagai arsip?
3. Apakah semua sheet OPSDA, BENDUNGAN, BALAI, PJSA, PJPA terbaca?
4. Apakah header terdeteksi walaupun tidak di baris pertama?
5. Apakah semua raw rows tersimpan?
6. Apakah kolom satker benar berasal dari nama sheet?
7. Apakah normalisasi kolom berjalan?
8. Apakah nilai rupiah dikonversi dengan benar?
9. Apakah nilai persen dikonversi dengan benar?
10. Apakah baris paket detail terdeteksi dengan benar?
11. Apakah baris kegiatan/KRO/RO/subtotal/total tidak masuk paket_progres?
12. Apakah KPI dashboard hanya membaca paket_progres?
13. Apakah ada query dashboard yang membaca raw_progres_rows untuk KPI?
14. Apakah metrics sisa_anggaran benar?
15. Apakah serapan terhadap pagu benar?
16. Apakah serapan terhadap pagu efisiensi benar?
17. Apakah gap fisik-keuangan benar?
18. Apakah risk scoring sesuai aturan?
19. Apakah filter dashboard bekerja?
20. Apakah export CSV/XLSX sesuai hasil filter?
21. Apakah data quality report dibuat?
22. Apakah error import tercatat?
23. Apakah dashboard tetap aman jika ada nilai kosong?
24. Apakah test utama berjalan?

Jika menemukan bug, perbaiki langsung.
Jangan menambahkan fitur baru sebelum bug import, cleaning, metric, dan double counting selesai.
```

---

# 21. Instruksi Kritis untuk Agent

```text
Jangan mengejar tampilan sebelum data benar.
Jangan membuat semua page sekaligus sebelum import stabil.
Jangan hitung KPI dari raw rows.
Jangan percaya header berada di baris pertama.
Jangan percaya semua baris Excel adalah paket.
Jangan hardcode angka KPI.
Jangan sembunyikan warning data quality.
Jangan menambahkan AI.
Jangan membuat API publik sebelum MVP dashboard stabil.
```

Prioritas eksekusi:

```text
1. Import benar.
2. Cleaning benar.
3. Paket detail benar.
4. KPI benar.
5. Risk scoring benar.
6. Dashboard tampil.
7. Filter/export.
8. QA dan dokumentasi.
```
