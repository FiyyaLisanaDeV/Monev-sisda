---
marp: true
theme: default
class: lead
paginate: true
backgroundColor: #ffffff
style: |
  h1 {
    color: #1e3a8a;
  }
  h2 {
    color: #2563eb;
  }
  th {
    background-color: #dbeafe;
    color: #1e3a8a;
  }
---

![w:150](https://laravel.com/img/logomark.min.svg)
# **SISDA MONEV**
### User Guide & Executive Overview
*Pantau Cepat, Kendali Tepat*

---

## 🎯 Apa itu SISDA Monev?

Sistem Informasi Sumber Daya Air (SISDA) Monev adalah dashboard **monitoring progres fisik, keuangan, dan risiko paket** pekerjaan.

Aplikasi ini dirancang untuk:
- Memberikan visibilitas langsung terhadap status proyek.
- Mengidentifikasi proyek dengan risiko tinggi (Kritis/Perlu Perhatian).
- Memudahkan audit dengan fitur Import Excel massal.

---

## 📊 1. Ringkasan Eksekutif
*Melihat gambaran besar dari seluruh proyek dalam satu layar.*

- **Total Pagu & Realisasi:** Pantau perbandingan dana dialokasikan dengan yang telah terserap.
- **Persentase Serapan:** Metrik utama keberhasilan finansial.
- **Distribusi Risiko:** Chart interaktif yang membagi paket menjadi 4 status warna:
  - 🟢 **Aman**
  - 🟡 **Perlu Perhatian**
  - 🔵 **Perlu Review**
  - 🔴 **Kritis**

---

## 🚨 2. Manajemen Paket Berisiko
*Tindakan proaktif untuk paket yang bermasalah.*

Buka modul **Paket Berisiko** untuk melihat fokus harian Anda:
- Daftar khusus memfilter paket dengan status **Kritis** dan **Perlu Perhatian**.
- Klik setiap baris untuk melihat **Popup Detail** tanpa meninggalkan halaman.
- Informasi popup mencakup *gap* (selisih) antara rencana dan realisasi fisik maupun keuangan.

---

## 🗺️ 3. Analisis Mendalam
*Menelusuri pola dari data yang ada.*

- **Analisis Lokasi:** Bandingkan progres dan risiko berdasarkan wilayah/kabupaten. Membantu identifikasi wilayah yang sering mengalami keterlambatan.
- **Analisis Jenis Paket:** Baca pola risiko berdasarkan tipe pekerjaan (misal: Irigasi, Bendungan, Pantai).

---

## 📥 4. Import & Master Data
*Kemudahan manajemen data secara masif.*

- **Import Batches:**
  - Unggah file `.xlsx` berisi progres ratusan paket sekaligus.
  - Proses berjalan di *background*, tidak membuat sistem lambat.
  - Dilengkapi *Audit Trail* untuk mengecek kapan terakhir kali data diperbarui dan dari file mana.
- **Master Data Paket:** Tabel komprehensif berisi seluruh proyek dengan fitur pencarian dan filter kolom canggih.

---

## 👥 5. Manajemen Akses & Keamanan
*Membatasi informasi sesuai wewenang.*

- **Admin:** Memiliki akses penuh ke seluruh fitur, dashboard, proses import, hingga **Manajemen User** (tambah/hapus pengguna).
- **Operator:** Mengakses fungsi utama dashboard dan pemantauan data tanpa wewenang mengubah struktur pengguna aplikasi.

---

# 🚀 Terima Kasih
Sistem ini terus diperbarui untuk mendukung efisiensi monitoring Anda.

**Ada Pertanyaan?**
*Silakan hubungi administrator sistem Anda.*
