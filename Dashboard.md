# 🏆 Project Showcase: Laravel Dashboard Monitoring Progres TA 2025

Dashboard Executive berbasis Laravel dan Filament ini dirancang khusus untuk menangani proses *ingestion* (impor) data Excel multilembar secara asinkron (Background Job), melakukan pembersihan data *(data cleaning)* otomatis, dan menyajikannya dalam sebuah antarmuka bergaya premium (Glassmorphism).

Tujuan utama sistem ini adalah **mencegah Double-Counting**, memastikan **Audit Trail (Jejak Audit) yang ketat**, dan menyajikan data mentah menjadi wawasan *Actionable* secara instan.

---

## ✨ 1. Estetika & UI/UX Premium
Sistem ini membuang tampilan aplikasi pemerintahan/korporat yang generik dan menggantinya dengan desain *Tech-Startup* modern:
- **Deep Dark Mode**: Menggunakan gradasi latar belakang dinamis dari `#0f172a` ke `#1e1b4b`.
- **Glassmorphism Design**: Semua kartu, grafik, dan tabel dibungkus dengan efek kaca tembus pandang (*backdrop-filter: blur*).
- **Custom Typography**: Menggunakan font geometris **Outfit** (Google Fonts) untuk memberikan kesan tegas namun elegan.
- **Micro-Animations**: Elemen akan merespons dengan efek mengangkat *(floating hover)* dan bersinar *(glowing)* ketika disentuh oleh kursor.

---

## ⚙️ 2. Arsitektur "Behind The Scenes"
Proses pengolahan data dikerjakan dengan sangat serius di balik layar agar pengguna akhir tidak pernah merasakan *loading* lambat:

1. **Asynchronous Processing**: File Excel diunggah ke *Storage*, lalu `ProcessProgresImportJob` dijalankan oleh *Queue Worker* di latar belakang.
2. **Fuzzy Header Detection**: Sistem secara otomatis mencari baris yang merupakan *Header* dari sebuah tabel tanpa peduli di baris ke berapa *header* tersebut berada, menggunakan deteksi kata kunci seperti `kode`, `paket`, dan `pagu`.
3. **Data Cleaning**: Angka acak seperti `Rp 1.500.000,50` dibersihkan secara matematis menjadi `1500000.50` agar bisa diagregasi di database.
4. **Anti Double-Counting**: Sistem secara cerdas mendeteksi baris agregat (seperti baris bertuliskan "TOTAL" atau "SUBTOTAL") dan **membuangnya** agar tidak ikut terhitung sebagai Paket Proyek, memastikan angka 100% akurat.

---

## 🛡️ 3. Immutable Audit Trail (Keamanan Data)
Karena data yang diolah menyangkut angka anggaran (Pagu & Realisasi), prinsip audit ditegakkan dengan sangat kaku:
- Semua baris Excel tanpa terkecuali difoto dan diamankan ke dalam tabel mentah (`raw_progres_rows`).
- File Excel asli akan diarsipkan dan tidak akan pernah *di-overwrite*.
- Tabel Data Mentah dan Paket Hasil **TIDAK BISA DIEDIT ATAU DIHAPUS** (Strict Read-Only). Bahkan akun level **Admin** pun dilarang keras mengubah angka ini melalui sistem. Kesalahan angka harus diperbaiki dari file Excel sumber lalu diunggah sebagai *Import Batch* baru.

---

## 🚦 4. Automated Risk Scoring
Alih-alih mengandalkan manusia untuk menyortir proyek bermasalah, sistem ini menyematkan algoritma pendeteksi risiko otomatis:
- 🔴 **Kritis (Score 3)**: Paket yang realisasinya $0, atau serapan fisiknya masih di bawah 70%.
- 🟠 **Perlu Perhatian (Score 2)**: Paket yang serapannya agak melambat (di bawah 90%).
- 🟡 **Perlu Review (Score 1)**: Paket yang memiliki penyimpangan jarak (Gap) yang jauh antara laporan persentase Fisik vs persentase Keuangan (>15%).
- 🟢 **Aman (Score 0)**: Berjalan lancar sesuai rel.

---

## 📈 5. Analitik Lanjutan
Lebih dari sekadar tabel, sistem telah dilengkapi *Custom Pages* yang siap saji:
- **Perbandingan Satker**: Membandingkan adu performa antar Satuan Kerja.
- **Analisis Lokasi**: Menemukan provinsi/kota mana yang menelan anggaran terbesar dan memiliki paket paling berisiko.
- **Distribusi Jenis Paket**: Memecah performa berdasarkan jenis lelang/pekerjaan.

---

> *"Sebuah contoh integrasi sempurna antara stabilitas Backend (Laravel), kecepatan Dashboarding (Filament), pemrosesan Asinkron (Queue), dan estetika Visual Kelas Atas."*
