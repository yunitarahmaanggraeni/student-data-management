# UNIVERSITAS PAMULANG - Editorial Digital Directory

Aplikasi **Student Data Management** premium berbasis Web (PHP Native) yang terintegrasi langsung dengan **Google Sheets** sebagai _database_ utama. Dirancang dengan konsep UI/UX _"Editorial Digital University"_ yang mengedepankan estetika kelas atas, tipografi modern, dan pengalaman pengguna yang interaktif.

## ✨ Features

- **Real-time Directory:** Menampilkan data mahasiswa secara langsung dari Google Sheets dengan format _interactive list_ (bukan tabel konvensional).
- **Seamless Edit & Delete:** Memperbarui atau menghapus baris data secara permanen langsung ke Google Sheets tanpa menyisakan baris kosong.
- **Smart Command Search:** Fitur pencarian data mahasiswa berdasarkan Nama atau Kelas yang cepat dan responsif.
- **Auto-Avatar Generation:** Membuat inisial avatar otomatis dari nama mahasiswa.
- **Editorial Micro-interactions:** Dilengkapi animasi halus saat kursor diarahkan (_hover_), transisi halaman, dan _Premium Toast Notification_.
- **100% Responsive:** Tampilan yang beradaptasi sempurna di layar desktop, tablet, maupun _smartphone_.

## 🛠️ Technology Stack

- **Backend:** PHP Native
- **Database:** Google Sheets API v4 (Google API PHP Client)
- **Frontend:** HTML5, CSS3 (Custom CSS Variables, Flexbox/Grid), JavaScript (Vanilla)
- **Typography:** Playfair Display & Inter (Google Fonts)

## ⚙️ Setup & Installation

1. Pastikan komputer telah terinstal XAMPP dan Composer.
2. _Clone_ atau unduh _repository_ ini ke dalam folder `htdocs` (jika menggunakan XAMPP).
3. Buka terminal di dalam folder project dan jalankan perintah untuk mengunduh _library_ Google:
   ```bash
   composer require google/apiclient
   ```
