# Sistem Informasi Manajemen dan Pelaporan Tugas - Dinas Sosial Kudus

Aplikasi web ini dikembangkan sebagai solusi digital untuk mengelola dan memantau pelaksanaan tugas antar bidang di lingkungan Dinas Sosial Kabupaten Kudus. Sistem ini menggantikan metode lama berbasis WhatsApp dengan platform berbasis web yang lebih terstruktur dan terdokumentasi. Aplikasi ini dibuat sebagai bagian dari kegiatan Praktik Kerja Lapangan (PKL).

📌 Fitur Utama

🔐 Login
- Terdapat dua jenis login: Admin dan User (Bidang).
- Pemilihan tahun periode pemerintahan dilakukan saat login.
- Admin:
  - Username dan password telah di-hardcode untuk alasan keamanan (hanya ada 1 admin).
    - admin_username = 'dinsosp3ap2kb'
    - admin_password = 'dinsos123'
  
  - User:
    - Dapat membuat akun baru.
    - Dapat mengganti password.

📂 Manajemen Tugas
- Admin:
  - CRUD tugas (create, read, update, delete).
  - Mengatur status tugas: `Pending`, `Completed`, `Accepted`, dan `Rejected`.
  - Mencetak laporan tugas ke dalam format PDF.
  - Menampilkan tugas berdasarkan bulan atau seluruh tahun.
- User (Bidang):
  - Menampilkan daftar tugas berdasarkan bulan atau satu tahun penuh.
  - Mengubah status tugas menjadi `Pending` atau `Completed`.
  - Status default tugas adalah `Pending`.

🔒 Autentikasi dan Keamanan
- Sistem login untuk masing-masing role.
- Fitur logout tersedia untuk semua pengguna.

🛠️ Teknologi yang Digunakan

- Frontend: HTML, CSS
- Backend: PHP (native PHP)
- Database: MySQL
- Library Tambahan: FPDF untuk export PDF
- Development Tools: XAMPP

## 📸 Cuplikan Tampilan

- Halaman login
  
  ![image](https://github.com/user-attachments/assets/4825f846-f7e6-4ef1-bca7-731040ef67a5)

- Dashboard admin
  
  ![image](https://github.com/user-attachments/assets/9949c90e-017e-4e9e-a67b-67001e1e26b1)

- Dashboard user (bidang)
  
  ![image](https://github.com/user-attachments/assets/e70ce228-ac6a-4317-a45c-d9a29eb6951c)

- Fitur cetak laporan
  
  ![image](https://github.com/user-attachments/assets/a24e9b4f-fb8c-4083-879c-affb91f43f5b)
  ![image](https://github.com/user-attachments/assets/9a5a6df6-2806-4017-bb29-49a2b3af0400)

🚀 Cara Menjalankan Aplikasi

1. Clone repository ini
2. Pindahkan folder ke direktori htdocs (jika menggunakan XAMPP).
3. Buat database di phpMyAdmin dan import file SQL.
4. Atur koneksi database di file konfigurasi.
5. Akses melalui browser dengan URL: http://localhost/nama-folder
