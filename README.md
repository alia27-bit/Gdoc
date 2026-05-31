Gdoc adalah aplikasi manajemen dokumen berbasis Laravel yang memungkinkan pengguna membuat, mengedit, menyimpan, dan mengelola dokumen secara online. Sistem juga menyediakan fitur riwayat versi (Version History) sehingga perubahan dokumen dapat dilacak dan dipulihkan kapan saja.

-Fitur
1. Autentikasi Login & Register
2. Membuat dokumen baru
3. Mengedit dokumen
4. Menyimpan dokumen secara otomatis
5. Version History
6. Restore versi dokumen sebelumnya
7. Penyimpanan snapshot dokumen
8. Dashboard manajemen dokumen

-Teknologi
1. PHP 8.2
2. Laravel 12
3. MySQL
4. Blade Template
5. Laravel Reverb

-Instalasi
1. Clone Repository
git clone https://github.com/alia27-bit/Gdoc.git
lalu masuk ke vscode cd Gdoc

2. Install Dependency
composer install
npm install

3. Konfigurasi Environment
Salin file .env.example menjadi .env
copy .env.example .env
Generate application key:
php artisan key:generate

4. Konfigurasi Database
Buka file .env lalu sesuaikan dengan :
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdoc
DB_USERNAME=root
DB_PASSWORD=

5. Jalankan Migrasi
php artisan migrate:fresh

6. Menjalankan Aplikasi
buka 4 terminal baru:
1. php artisan serve
    menjalankan aplikasi laravel nya 
    Akses aplikasi melalui: http://127.0.0.1:8000
2. npm run dev
    untuk menjalannkan vite untuk css dan JavaScript
3. php artisan reverb:start
    Menjalankan Laravel Reverb (WebSocket laravel)
4. cd websocket-server, node server.js
    Menjalankan server kolaborasi dokumen secara real-time

-Struktur Fitur
Dokumen
1. Membuat dokumen baru
2. Mengubah isi dokumen
3. Menghapus dokumen

-Version History
1. Menyimpan versi dokumen
2. Melihat riwayat perubahan
3. Mengembalikan versi sebelumnya

Nama: Alia Rohali Hasibuan
Nim : 240180055
Mk  : Pemrograan Web 2

