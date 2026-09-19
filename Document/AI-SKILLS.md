# BravePay AI Skills Documentation

Dokumen ini menggabungkan pembaruan dan aturan dari skill AI Laravel Boost yang digunakan di proyek BravePay.

## Sumber Skill

Skill aktif tersimpan di:

- `.kiro/skills/`
- `Document/.claude/skills/`
- `AGENTS.md`
- `CLAUDE.md`

File sumber tetap dipertahankan karena digunakan oleh agent AI. Dokumen ini menjadi referensi terpusat untuk tim pengembang.

## Aturan Umum Proyek

- Gunakan Laravel dan API yang sesuai dengan versi package yang terpasang.
- Ikuti pola yang sudah digunakan oleh kode di sekitarnya.
- Gunakan nama variabel, method, class, dan file yang deskriptif.
- Pilih perubahan terkecil yang menyelesaikan kebutuhan.
- Jangan menambah dependency atau abstraksi tanpa kebutuhan nyata.
- Pertahankan struktur folder Laravel yang sudah ada.
- Gunakan route bernama dan helper `route()` untuk membuat URL.
- Jangan menambahkan dokumentasi baru kecuali memang diminta.
- Jalankan validasi terdekat setelah perubahan dibuat.

## Laravel Best Practices

### Arsitektur

- Gunakan fitur framework dan abstraksi aplikasi yang sudah ada.
- Hindari repository, service, atau helper baru jika controller dan Eloquent sudah sesuai dengan pola aplikasi.
- Buat abstraksi hanya ketika menghilangkan duplikasi yang berarti atau membentuk batas domain yang jelas.
- Pertahankan pemisahan tanggung jawab antara controller, model, view, migration, dan test.

### Controller dan Routing

- Gunakan controller untuk mengatur alur request dan response.
- Gunakan route bernama untuk navigasi antarhalaman.
- Gunakan route model binding jika sesuai dengan pola proyek.
- Validasi akses dan input pada batas HTTP.
- Pastikan route write action menggunakan method HTTP yang tepat.
- Untuk endpoint yang mengubah data, tambahkan test feature yang menguji request sebenarnya.

### Eloquent dan Database

- Gunakan Eloquent secara langsung sesuai pola aplikasi.
- Gunakan eager loading untuk relasi yang ditampilkan agar terhindar dari N+1 query.
- Letakkan query di controller atau abstraction yang sudah digunakan proyek, bukan di Blade.
- Gunakan migration untuk setiap perubahan skema.
- Tambahkan foreign key dan perilaku penghapusan yang sesuai.
- Gunakan index pada kolom yang sering digunakan untuk pencarian, filter, atau relasi.
- Gunakan cast model untuk JSON, tanggal, angka, dan tipe data yang sesuai.
- Gunakan transaksi untuk beberapa perubahan database yang harus berhasil bersama.

### Validasi dan Keamanan

- Validasi semua input dari user.
- Jangan mempercayai ID, status, harga, atau nilai lain dari client.
- Batasi mass assignment melalui `$fillable` atau konfigurasi attribute yang sudah dipakai model.
- Terapkan middleware `auth` pada halaman admin.
- Pastikan aktivitas penting admin dapat dilacak pada audit log.
- Jangan menyimpan password, token, atau secret ke dalam log atau dokumentasi.
- Escape output pada Blade dan hindari HTML mentah dari input user.

### Error Handling

- Gunakan response Laravel standar seperti redirect, validation error, `firstOrFail()`, dan response status yang sesuai.
- Jangan menyembunyikan error dengan catch yang tidak melakukan apa-apa.
- Tampilkan pesan user-friendly pada UI tanpa membocorkan detail internal.
- Catat informasi teknis yang diperlukan pada log aplikasi.

### Konfigurasi dan Dependency

- Ambil nilai environment melalui konfigurasi Laravel.
- Jangan membaca environment langsung di banyak tempat aplikasi.
- Sebelum memakai API package baru, periksa versi package melalui Composer atau `composer show`.
- Jangan mengubah dependency tanpa persetujuan.

## Testing Best Practices

### Prinsip Test

- Tulis feature test untuk perilaku yang dapat diakses melalui HTTP.
- Gunakan unit test hanya untuk logika yang tidak membutuhkan framework.
- Test harus memeriksa perilaku yang terlihat oleh pengguna atau kontrak aplikasi.
- Jangan menguji ulang perilaku framework yang tidak dikustomisasi oleh aplikasi.
- Pertahankan test yang mendeteksi defect berbeda.

### Struktur Test

- Baca test di folder yang sama sebelum membuat test baru.
- Ikuti framework test dan gaya assertion yang digunakan proyek.
- Gunakan nama test yang menjelaskan perilaku.
- Gunakan pola Arrange, Act, Assert.
- Gunakan factory yang tersedia untuk membuat data test.
- Gunakan `RefreshDatabase` untuk test database yang membutuhkan isolasi.

### Coverage

- Cover setiap keputusan penting: validasi, authorization, kalkulasi, routing, dan perubahan status.
- Test endpoint yang membutuhkan autentikasi dengan user yang benar.
- Test kegagalan penting, termasuk data tidak ditemukan, input tidak valid, dan akses tanpa autentikasi.
- Jalankan test paling sempit terlebih dahulu, kemudian suite yang lebih luas jika diperlukan.

### Test Security

- Pastikan user tanpa akses tidak dapat menjalankan endpoint admin.
- Pastikan data milik resource lain tidak dapat diakses melalui perubahan parameter URL.
- Test validasi dan pencegahan mass assignment jika behavior tersebut dikustomisasi.
- Jangan memasukkan credential atau secret nyata ke test.

## Tailwind CSS Development

- Gunakan utility Tailwind yang konsisten dengan view yang berdekatan.
- Periksa versi Tailwind sebelum memakai utility atau konfigurasi baru.
- Untuk Tailwind v4, gunakan konfigurasi CSS-first dengan `@theme` dan `@import "tailwindcss"`.
- Hindari utility yang sudah deprecated seperti `flex-shrink-*`, `bg-opacity-*`, dan `text-opacity-*`.
- Gunakan `shrink-*` sebagai pengganti `flex-shrink-*`.
- Gunakan `gap-*` untuk jarak antar sibling pada layout flex atau grid.
- Jika proyek memiliki dark mode, komponen baru harus mengikuti variant `dark:` yang sudah digunakan.
- Gunakan komponen Blade yang sudah tersedia untuk pola UI berulang.
- Pastikan tabel, form, sidebar, dan layout tetap responsif pada layar kecil.

## Infer Conventions

Skill infer-conventions digunakan untuk mendokumentasikan kebiasaan nyata codebase, bukan untuk memaksakan gaya baru.

Aturannya:

- Baca konfigurasi tooling sebelum menyimpulkan style kode.
- Catat hanya pola yang memiliki bukti konsisten dan bukan sekadar default Laravel.
- Jika ada dua pola yang sama-sama digunakan, laporkan sebagai conflict dan jangan memilih salah satu tanpa keputusan tim.
- Prioritaskan keputusan arsitektur seperti penggunaan service, action, query object, DTO, atau repository.
- Scope aturan ke folder yang paling spesifik.
- Jangan menduplikasi aturan yang sudah ada.
- Dokumentasikan konvensi, bukan daftar file bukti atau statistik pencarian.

## Deploying to Laravel Cloud

Jika proyek akan dideploy ke Laravel Cloud:

- Aktifkan skill deployment yang sesuai.
- Periksa environment production, database, storage, queue, mail, dan secret.
- Pastikan migration dapat dijalankan di environment target.
- Pastikan asset frontend dibuild sebelum deploy.
- Verifikasi health check dan konfigurasi domain setelah deploy.
- Jangan mencetak atau membagikan secret deployment.

## Alur Kerja AI yang Direkomendasikan

1. Baca file yang menjadi anchor permintaan user.
2. Cari implementasi terdekat, route, model, view, dan test terkait.
3. Rumuskan satu hipotesis lokal tentang penyebab atau perilaku yang diinginkan.
4. Pilih pemeriksaan termurah yang dapat membuktikan atau membantah hipotesis tersebut.
5. Buat perubahan sekecil mungkin.
6. Jalankan test atau validasi fokus segera setelah edit.
7. Jalankan formatter untuk file PHP yang berubah:

   ```powershell
   vendor/bin/pint --dirty --format agent
   ```

8. Jalankan test feature yang relevan:

   ```powershell
   php artisan test --compact tests/Feature/NamaTest.php
   ```

9. Periksa route dan error aplikasi jika perubahan menyentuh routing atau view.
10. Ringkas file yang berubah, validasi yang berhasil, dan masalah yang masih tersisa.

## Perintah Verifikasi Umum

```powershell
php -v
composer -V
php artisan route:list --except-vendor
php artisan migrate:status
php artisan test --compact
vendor/bin/pint --dirty --format agent
```

## Catatan Pemeliharaan

Jika skill AI diperbarui, sinkronkan perubahan penting ke dokumen ini. Jangan menghapus `.kiro/skills` atau `Document/.claude/skills` karena agent dapat membaca file tersebut secara langsung.
