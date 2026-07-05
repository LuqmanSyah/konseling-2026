# Product Requirements Document

## Sistem Booking dan Manajemen Layanan Konseling Mahasiswa

## 1. Identitas Dokumen

| Item | Keterangan |
| --- | --- |
| Nama Dokumen | Product Requirements Document |
| Nama Produk | Sistem Booking dan Manajemen Layanan Konseling Mahasiswa |
| Versi | 1.0 |
| Status | Draft |
| Jenis Sistem | Aplikasi Web |
| Scope | MVP |
| Fokus Sistem | Booking konseling mahasiswa secara self-service |
| Role Pengguna | Super Admin, Admin BKTS, Konselor, Mahasiswa |

## 2. Ringkasan Produk

Sistem Booking dan Manajemen Layanan Konseling Mahasiswa adalah aplikasi web yang digunakan untuk membantu mahasiswa melakukan pengajuan layanan konseling secara mandiri.

Pada versi MVP, sistem hanya berfokus pada jalur self-service, yaitu mahasiswa dapat login, mengajukan konseling, memilih kategori konseling, memilih metode konseling, memilih jadwal yang tersedia, dan memantau status booking.

Admin BKTS berperan dalam mengelola jadwal, memverifikasi pengajuan, mengubah status booking, mencatat notifikasi simulasi, serta melihat laporan dasar layanan konseling.

Konselor berperan dalam melihat jadwal konseling yang ditugaskan, melihat detail pengajuan mahasiswa, mencatat hasil konseling, memberikan rekomendasi tindak lanjut, dan menandai booking sebagai selesai atau dirujuk.

Super Admin berperan dalam mengelola akun, role, data master, dan pengaturan dasar sistem.

Pada tahap MVP, sistem tidak melakukan integrasi asli dengan WhatsApp API, Google Calendar, Google Meet, Zoom, atau Email. Semua fitur notifikasi dan link meeting dibuat dalam bentuk simulasi.

## 3. Problem Statement

Proses pengajuan layanan konseling mahasiswa yang masih dilakukan secara manual berisiko menyebabkan data pengajuan tidak terdokumentasi dengan baik, jadwal sulit dipantau, status pengajuan tidak transparan bagi mahasiswa, serta catatan hasil konseling sulit dikelola secara terstruktur.

Mahasiswa membutuhkan sistem yang mudah digunakan untuk mengajukan konseling dan melihat status pengajuannya. Admin BKTS membutuhkan sistem untuk mengelola pengajuan dan jadwal secara terpusat. Konselor membutuhkan sistem untuk melihat sesi yang ditugaskan serta mencatat hasil konseling secara aman.

## 4. Goals / Tujuan Produk

Tujuan produk ini adalah:

- Mempermudah mahasiswa dalam mengajukan layanan konseling secara mandiri.
- Menyediakan sistem booking konseling yang terpusat dan terdokumentasi.
- Memudahkan Admin BKTS dalam mengelola jadwal dan pengajuan konseling.
- Memudahkan Admin BKTS dalam memverifikasi dan mengubah status booking.
- Memudahkan konselor dalam melihat jadwal konseling yang ditugaskan.
- Menyediakan fitur pencatatan hasil konseling oleh konselor.
- Menyediakan pencatatan rujukan atau tindak lanjut secara sederhana.
- Menjaga kerahasiaan catatan konseling melalui pembatasan akses.
- Menyediakan laporan dasar layanan konseling.
- Menyediakan simulasi notifikasi dan link meeting untuk kebutuhan demonstrasi MVP.

## 5. User Roles

| Role | Deskripsi |
| --- | --- |
| Super Admin | Pengguna yang mengelola akun, role, data master, dan pengaturan dasar sistem. |
| Admin BKTS | Pengguna yang mengelola operasional layanan konseling, termasuk jadwal, pengajuan, status booking, notifikasi simulasi, rujukan, dan laporan. |
| Konselor | Pengguna yang menangani sesi konseling, melihat jadwal yang ditugaskan, mencatat hasil konseling, dan memberikan rekomendasi tindak lanjut. |
| Mahasiswa | Pengguna yang mengajukan konseling, memilih jadwal, melihat status booking, melihat link meeting simulasi, dan melihat riwayat pengajuan. |

## 6. Product Scope

### 6.1 In Scope MVP

Fitur yang termasuk dalam MVP:

- Login pengguna berdasarkan role.
- Dashboard sesuai role pengguna.
- Pengelolaan akun oleh Super Admin.
- Pengelolaan data master dasar.
- Pengelolaan jadwal konseling oleh Admin BKTS.
- Pengajuan konseling mandiri oleh mahasiswa.
- Pemilihan kategori konseling oleh mahasiswa.
- Pemilihan metode konseling oleh mahasiswa.
- Pemilihan jadwal tersedia oleh mahasiswa.
- Verifikasi pengajuan oleh Admin BKTS.
- Perubahan status booking oleh Admin BKTS.
- Link meeting simulasi untuk konseling online.
- Pencatatan notifikasi simulasi.
- Tampilan jadwal konseling untuk konselor.
- Pencatatan hasil konseling oleh konselor.
- Pencatatan rekomendasi tindak lanjut.
- Pencatatan rujukan sederhana.
- Riwayat pengajuan konseling mahasiswa.
- Riwayat sesi konseling konselor.
- Laporan dasar layanan konseling.

### 6.2 Out of Scope MVP

Fitur yang tidak termasuk dalam MVP:

- Jalur proaktif BKTS berdasarkan data mahasiswa bermasalah.
- Deteksi otomatis mahasiswa bermasalah berdasarkan IPK, absensi, atau status aktif.
- Dashboard Prodi.
- Dashboard Psikolog.
- Dashboard BAP, BKS, atau unit lain.
- Integrasi WhatsApp API asli.
- Integrasi Email asli.
- Integrasi Google Calendar asli.
- Generate Google Meet asli.
- Integrasi Zoom asli.
- Reminder otomatis asli.
- Reschedule mandiri oleh mahasiswa.
- Chat real-time antara mahasiswa dan konselor.
- Audit trail lengkap.
- Riwayat perubahan status detail.
- Diagnosis psikologis otomatis.
- Aplikasi mobile Android atau iOS.
- Integrasi SIAKAD asli.

## 7. User Flow

### 7.1 Flow Mahasiswa

1. Mahasiswa membuka halaman login.
2. Mahasiswa login menggunakan akun yang tersedia.
3. Sistem menampilkan dashboard mahasiswa.
4. Mahasiswa membuka menu pengajuan konseling.
5. Mahasiswa memilih kategori konseling:
   - Akademik
   - Non-Akademik
6. Mahasiswa memilih metode konseling:
   - Online
   - Tatap muka
7. Sistem menampilkan daftar jadwal yang tersedia.
8. Mahasiswa memilih salah satu jadwal.
9. Mahasiswa mengisi keluhan awal atau ringkasan masalah.
10. Mahasiswa mengirim pengajuan.
11. Sistem menyimpan pengajuan dengan status awal `Diajukan`.
12. Mahasiswa melihat status pengajuan pada dashboard atau menu riwayat.
13. Jika pengajuan disetujui, mahasiswa dapat melihat status `Dijadwalkan`.
14. Jika metode online, mahasiswa dapat melihat link meeting simulasi.
15. Mahasiswa dapat melihat riwayat pengajuan konseling.

### 7.2 Flow Admin BKTS

1. Admin BKTS login ke sistem.
2. Sistem menampilkan dashboard Admin BKTS.
3. Admin BKTS membuka menu jadwal konseling.
4. Admin BKTS membuat jadwal konseling.
5. Admin BKTS menentukan tanggal, jam, metode, dan konselor.
6. Jadwal yang dibuat akan tampil sebagai jadwal tersedia untuk mahasiswa.
7. Admin BKTS membuka menu pengajuan konseling.
8. Admin BKTS melihat daftar pengajuan dengan status `Diajukan`.
9. Admin BKTS membuka detail pengajuan.
10. Admin BKTS dapat menyetujui atau membatalkan pengajuan.
11. Jika disetujui, status berubah menjadi `Dijadwalkan`.
12. Jika metode online, sistem membuat link meeting simulasi.
13. Sistem mencatat notifikasi simulasi.
14. Admin BKTS dapat melihat riwayat booking.
15. Admin BKTS dapat melihat laporan dasar layanan konseling.
16. Admin BKTS dapat melihat atau mencatat rujukan jika diperlukan.

### 7.3 Flow Konselor

1. Konselor login ke sistem.
2. Sistem menampilkan dashboard konselor.
3. Konselor membuka menu jadwal konseling.
4. Konselor melihat daftar booking yang ditugaskan kepadanya.
5. Konselor membuka detail pengajuan mahasiswa.
6. Konselor melaksanakan sesi konseling sesuai jadwal.
7. Setelah sesi dilakukan, konselor mengisi catatan hasil konseling.
8. Konselor mengisi rekomendasi tindak lanjut.
9. Jika sesi selesai tanpa tindak lanjut, booking ditandai sebagai `Selesai`.
10. Jika mahasiswa membutuhkan tindak lanjut, booking ditandai sebagai `Dirujuk`.
11. Konselor dapat melihat riwayat sesi konseling yang pernah ditangani.

### 7.4 Flow Super Admin

1. Super Admin login ke sistem.
2. Sistem menampilkan dashboard Super Admin.
3. Super Admin membuka menu manajemen pengguna.
4. Super Admin dapat menambah, mengubah, menonaktifkan, dan menghapus akun sesuai kebutuhan.
5. Super Admin dapat mengelola role pengguna.
6. Super Admin dapat mengelola data Admin BKTS.
7. Super Admin dapat mengelola data Konselor.
8. Super Admin dapat mengelola data Mahasiswa.
9. Super Admin dapat mengelola data master dasar seperti kategori konseling dan metode konseling.
10. Super Admin dapat mengelola pengaturan dasar sistem.

## 8. Feature Requirements

| Kode | Fitur | Deskripsi | Role | Prioritas |
| --- | --- | --- | --- | --- |
| FR-01 | Login | Pengguna dapat login sesuai role. | Semua role | High |
| FR-02 | Dashboard Role | Sistem menampilkan dashboard sesuai role. | Semua role | High |
| FR-03 | Manajemen Pengguna | Super Admin dapat mengelola akun pengguna. | Super Admin | High |
| FR-04 | Manajemen Data Master | Super Admin dapat mengelola data master dasar. | Super Admin | Medium |
| FR-05 | Manajemen Jadwal | Admin BKTS dapat membuat dan mengelola jadwal konseling. | Admin BKTS | High |
| FR-06 | Pengajuan Konseling | Mahasiswa dapat mengajukan konseling mandiri. | Mahasiswa | High |
| FR-07 | Pilih Jadwal | Mahasiswa dapat memilih jadwal yang tersedia. | Mahasiswa | High |
| FR-08 | Verifikasi Pengajuan | Admin BKTS dapat menyetujui atau membatalkan pengajuan. | Admin BKTS | High |
| FR-09 | Status Booking | Sistem dapat mengelola status booking. | Admin BKTS, Konselor | High |
| FR-10 | Link Meeting Simulasi | Sistem membuat link meeting simulasi untuk konseling online. | Sistem, Mahasiswa, Admin BKTS, Konselor | Medium |
| FR-11 | Notifikasi Simulasi | Sistem mencatat notifikasi simulasi. | Sistem, Admin BKTS | Medium |
| FR-12 | Jadwal Konselor | Konselor dapat melihat jadwal yang ditugaskan. | Konselor | High |
| FR-13 | Catatan Konseling | Konselor dapat mengisi catatan hasil konseling. | Konselor | High |
| FR-14 | Rekomendasi Tindak Lanjut | Konselor dapat memberikan rekomendasi tindak lanjut. | Konselor | High |
| FR-15 | Rujukan | Konselor atau Admin BKTS dapat mencatat rujukan. | Konselor, Admin BKTS | Medium |
| FR-16 | Riwayat Mahasiswa | Mahasiswa dapat melihat riwayat pengajuan. | Mahasiswa | High |
| FR-17 | Riwayat Konselor | Konselor dapat melihat riwayat sesi yang pernah ditangani. | Konselor | Medium |
| FR-18 | Laporan Dasar | Admin BKTS dapat melihat laporan dasar layanan konseling. | Admin BKTS | Medium |
| FR-19 | Access Control | Sistem membatasi akses berdasarkan role. | Semua role | High |

## 9. Detailed Feature Specification

### 9.1 Login

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Memastikan hanya pengguna terdaftar yang dapat mengakses sistem. |
| Aktor | Super Admin, Admin BKTS, Konselor, Mahasiswa |
| Input | Email atau username, password |
| Proses | Sistem memvalidasi kredensial dan role pengguna. |
| Output | Pengguna diarahkan ke dashboard sesuai role. |
| Validasi | Email atau username wajib diisi, password wajib diisi, akun harus aktif. |
| Error State | Jika login gagal, sistem menampilkan pesan kesalahan. |

### 9.2 Dashboard

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Menampilkan ringkasan informasi sesuai role. |
| Aktor | Semua role |
| Input | Data pengguna login |
| Proses | Sistem mengambil data sesuai hak akses role. |
| Output | Dashboard khusus Super Admin, Admin BKTS, Konselor, atau Mahasiswa. |

Dashboard Mahasiswa menampilkan:

- Tombol ajukan konseling.
- Status pengajuan terbaru.
- Jadwal konseling aktif.
- Riwayat pengajuan singkat.

Dashboard Admin BKTS menampilkan:

- Total pengajuan.
- Pengajuan menunggu verifikasi.
- Booking dijadwalkan.
- Booking selesai.
- Booking dibatalkan.
- Booking dirujuk.

Dashboard Konselor menampilkan:

- Jadwal konseling hari ini.
- Booking yang ditugaskan.
- Riwayat sesi konseling.
- Sesi yang belum diisi catatan.

Dashboard Super Admin menampilkan:

- Jumlah pengguna.
- Jumlah role.
- Data master aktif.
- Ringkasan akun aktif dan nonaktif.

### 9.3 Manajemen Pengguna

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Mengelola akun pengguna sistem. |
| Aktor | Super Admin |
| Input | Nama, email, password, role, status akun |
| Proses | Super Admin membuat, mengubah, menonaktifkan, atau menghapus akun. |
| Output | Data akun tersimpan di sistem. |
| Validasi | Email harus unik, role wajib dipilih, status akun wajib ditentukan. |

### 9.4 Manajemen Jadwal Konseling

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Menyediakan jadwal konseling yang dapat dipilih mahasiswa. |
| Aktor | Admin BKTS |
| Input | Tanggal, jam mulai, jam selesai, metode, konselor, status jadwal |
| Proses | Admin BKTS membuat jadwal dan menentukan konselor. |
| Output | Jadwal tersedia tampil pada form pengajuan mahasiswa. |
| Validasi | Tanggal dan jam wajib diisi, konselor wajib dipilih, jadwal tidak boleh bentrok untuk konselor yang sama. |

Status jadwal:

- `Tersedia`
- `Terpakai`
- `Tidak Aktif`

### 9.5 Pengajuan Konseling

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Mahasiswa dapat mengajukan layanan konseling secara mandiri. |
| Aktor | Mahasiswa |
| Input | Kategori konseling, metode konseling, jadwal, keluhan awal |
| Proses | Sistem menyimpan pengajuan dan mengubah jadwal menjadi terpakai sementara. |
| Output | Booking baru dengan status `Diajukan`. |
| Validasi | Semua field wajib diisi, jadwal harus tersedia, keluhan awal wajib diisi. |

Kategori konseling:

- Akademik
- Non-Akademik

Metode konseling:

- Online
- Tatap muka

### 9.6 Verifikasi Pengajuan

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Admin BKTS dapat memproses pengajuan mahasiswa. |
| Aktor | Admin BKTS |
| Input | Keputusan verifikasi, catatan admin bila diperlukan |
| Proses | Admin BKTS menyetujui atau membatalkan pengajuan. |
| Output | Status berubah menjadi `Dijadwalkan` atau `Dibatalkan`. |
| Validasi | Pengajuan harus berstatus `Diajukan`. |

Jika pengajuan disetujui:

- Status booking menjadi `Dijadwalkan`.
- Jadwal menjadi `Terpakai`.
- Jika metode online, sistem membuat link meeting simulasi.
- Sistem mencatat notifikasi simulasi.

Jika pengajuan dibatalkan:

- Status booking menjadi `Dibatalkan`.
- Jadwal kembali menjadi `Tersedia`.
- Sistem mencatat notifikasi simulasi pembatalan.

### 9.7 Link Meeting Simulasi

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Menyediakan link meeting simulasi untuk konseling online. |
| Aktor | Sistem |
| Kondisi | Booking disetujui dan metode konseling adalah online. |
| Format | `meet.mock/{kode_booking}` atau `zoom.mock/{kode_booking}` |
| Output | Link tampil pada detail booking mahasiswa, Admin BKTS, dan konselor. |

Link meeting simulasi tidak terhubung ke Google Meet atau Zoom asli.

### 9.8 Notifikasi Simulasi

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Mencatat seolah-olah sistem mengirim notifikasi kepada pengguna. |
| Aktor | Sistem, Admin BKTS |
| Input | Jenis notifikasi, penerima, isi pesan, status simulasi |
| Proses | Sistem membuat record notifikasi. |
| Output | Data notifikasi tersimpan dan dapat dilihat Admin BKTS. |

Kondisi pencatatan notifikasi:

- Pengajuan berhasil dibuat.
- Pengajuan disetujui.
- Booking dibatalkan.
- Booking selesai.
- Booking dirujuk.

### 9.9 Catatan Hasil Konseling

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Konselor dapat mencatat hasil sesi konseling. |
| Aktor | Konselor |
| Input | Catatan hasil konseling, rekomendasi tindak lanjut |
| Proses | Konselor mengisi catatan setelah sesi dilakukan. |
| Output | Catatan hasil konseling tersimpan. |
| Validasi | Catatan hanya dapat diisi oleh konselor yang ditugaskan. |

Catatan hasil konseling bersifat rahasia dan tidak dapat dilihat oleh mahasiswa.

### 9.10 Rujukan

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Mencatat tindak lanjut apabila mahasiswa perlu dirujuk. |
| Aktor | Konselor, Admin BKTS |
| Input | Tujuan rujukan, alasan rujukan, ringkasan tindak lanjut |
| Proses | Sistem menyimpan data rujukan. |
| Output | Status booking dapat berubah menjadi `Dirujuk`. |

Tujuan rujukan dapat berupa:

- Psikolog
- Prodi
- Unit akademik
- Unit keuangan
- Unit lain yang relevan

Rujukan hanya berupa pencatatan manual dan tidak membuat dashboard baru untuk pihak tujuan rujukan.

### 9.11 Laporan Dasar

| Item | Spesifikasi |
| --- | --- |
| Tujuan | Menyediakan ringkasan layanan konseling. |
| Aktor | Admin BKTS |
| Input | Data booking dan jadwal |
| Proses | Sistem menghitung data berdasarkan status, kategori, metode, dan rujukan. |
| Output | Laporan dasar dalam bentuk tabel atau ringkasan angka. |

## 10. Status Booking

| Status | Keterangan | Aktor yang Dapat Mengubah |
| --- | --- | --- |
| Diajukan | Mahasiswa sudah mengirim pengajuan dan menunggu verifikasi Admin BKTS. | Sistem |
| Dijadwalkan | Pengajuan sudah disetujui dan memiliki jadwal konseling. | Admin BKTS |
| Selesai | Sesi konseling sudah dilaksanakan dan catatan hasil konseling sudah diisi. | Konselor |
| Dirujuk | Mahasiswa membutuhkan tindak lanjut ke pihak lain. | Konselor, Admin BKTS |
| Dibatalkan | Pengajuan atau booking dibatalkan oleh Admin BKTS. | Admin BKTS |

Alur perubahan status:

- `Diajukan` -> `Dijadwalkan`
- `Diajukan` -> `Dibatalkan`
- `Dijadwalkan` -> `Selesai`
- `Dijadwalkan` -> `Dirujuk`
- `Dijadwalkan` -> `Dibatalkan`

## 11. Business Rules

- Sistem MVP hanya menggunakan jalur self-service mahasiswa.
- Mahasiswa wajib login sebelum mengajukan konseling.
- Mahasiswa hanya dapat memilih jadwal dengan status tersedia.
- Pengajuan baru selalu memiliki status awal `Diajukan`.
- Admin BKTS bertanggung jawab memverifikasi pengajuan.
- Booking yang disetujui berubah menjadi `Dijadwalkan`.
- Booking yang dibatalkan berubah menjadi `Dibatalkan`.
- Jika booking dibatalkan, jadwal kembali menjadi tersedia.
- Konselor hanya dapat melihat booking yang ditugaskan kepadanya.
- Konselor hanya dapat mengisi catatan untuk booking yang ditugaskan kepadanya.
- Catatan konseling hanya dapat dilihat oleh Admin BKTS dan konselor yang berwenang.
- Mahasiswa tidak dapat melihat catatan rahasia konselor.
- Mahasiswa hanya dapat melihat status umum, jadwal, metode, dan link meeting simulasi jika tersedia.
- Link meeting simulasi hanya dibuat untuk metode konseling online.
- Link meeting simulasi hanya dibuat setelah booking disetujui.
- Notifikasi pada MVP hanya berupa data simulasi yang tercatat di sistem.
- Rujukan hanya dicatat sebagai tindak lanjut manual.
- Rujukan tidak membuat dashboard untuk Prodi, Psikolog, BAP, BKS, atau unit lain.
- Super Admin tidak menangani proses konseling harian.
- Admin BKTS mengelola operasional layanan konseling harian.
- Sistem harus membatasi akses berdasarkan role pengguna.
- Jadwal yang sudah dipilih mahasiswa tidak dapat dipilih mahasiswa lain.
- Satu jadwal hanya dapat digunakan untuk satu booking aktif.
- Konselor tidak boleh memiliki jadwal bentrok pada waktu yang sama.
- Data pribadi dan catatan konseling harus dijaga kerahasiaannya.

## 12. Page / Screen Requirements

### 12.1 Halaman Login

Komponen:

- Input email atau username.
- Input password.
- Tombol login.
- Pesan error login.

Akses:

- Semua role.

### 12.2 Dashboard Mahasiswa

Komponen:

- Ringkasan status pengajuan terbaru.
- Tombol ajukan konseling.
- Informasi jadwal konseling aktif.
- Riwayat pengajuan singkat.

Akses:

- Mahasiswa.

### 12.3 Halaman Pengajuan Konseling

Komponen:

- Dropdown kategori konseling.
- Dropdown metode konseling.
- Daftar jadwal tersedia.
- Textarea keluhan awal.
- Tombol kirim pengajuan.
- Validasi form.

Akses:

- Mahasiswa.

### 12.4 Halaman Riwayat Pengajuan Mahasiswa

Komponen:

- Daftar pengajuan konseling.
- Status booking.
- Tanggal dan jam konseling.
- Metode konseling.
- Link meeting simulasi jika online dan sudah dijadwalkan.
- Detail pengajuan.

Akses:

- Mahasiswa.

### 12.5 Dashboard Admin BKTS

Komponen:

- Jumlah pengajuan masuk.
- Jumlah pengajuan menunggu verifikasi.
- Jumlah booking dijadwalkan.
- Jumlah booking selesai.
- Jumlah booking dibatalkan.
- Jumlah booking dirujuk.

Akses:

- Admin BKTS.

### 12.6 Halaman Manajemen Jadwal

Komponen:

- Daftar jadwal konseling.
- Form tambah jadwal.
- Form edit jadwal.
- Tanggal.
- Jam mulai.
- Jam selesai.
- Metode konseling.
- Pilihan konselor.
- Status jadwal.

Akses:

- Admin BKTS.

### 12.7 Halaman Verifikasi Pengajuan

Komponen:

- Daftar pengajuan dengan status `Diajukan`.
- Detail mahasiswa.
- Detail kategori konseling.
- Detail metode konseling.
- Detail jadwal.
- Keluhan awal.
- Tombol setujui.
- Tombol batalkan.
- Input alasan pembatalan jika dibatalkan.

Akses:

- Admin BKTS.

### 12.8 Halaman Detail Booking Admin BKTS

Komponen:

- Data mahasiswa.
- Data konselor.
- Kategori konseling.
- Metode konseling.
- Jadwal konseling.
- Status booking.
- Link meeting simulasi jika tersedia.
- Catatan rujukan jika ada.
- Tombol ubah status sesuai aturan.
- Riwayat umum booking.

Akses:

- Admin BKTS.

### 12.9 Dashboard Konselor

Komponen:

- Jadwal konseling hari ini.
- Daftar booking yang ditugaskan.
- Sesi yang belum diisi catatan.
- Riwayat sesi konseling.

Akses:

- Konselor.

### 12.10 Halaman Jadwal Konselor

Komponen:

- Daftar jadwal yang ditugaskan.
- Nama mahasiswa.
- Tanggal dan jam konseling.
- Metode konseling.
- Status booking.
- Link meeting simulasi jika online.

Akses:

- Konselor.

### 12.11 Halaman Catatan Konseling

Komponen:

- Detail booking.
- Keluhan awal mahasiswa.
- Input catatan hasil konseling.
- Input rekomendasi tindak lanjut.
- Pilihan status `Selesai` atau `Dirujuk`.
- Form rujukan jika diperlukan.

Akses:

- Konselor.

### 12.12 Halaman Manajemen Pengguna

Komponen:

- Daftar pengguna.
- Form tambah pengguna.
- Form edit pengguna.
- Role pengguna.
- Status akun.
- Tombol aktif/nonaktif.

Akses:

- Super Admin.

### 12.13 Halaman Data Master

Komponen:

- Data kategori konseling.
- Data metode konseling.
- Data role.
- Pengaturan dasar sistem.

Akses:

- Super Admin.

### 12.14 Halaman Laporan

Komponen:

- Total pengajuan konseling.
- Total booking berdasarkan status.
- Total booking berdasarkan kategori.
- Total booking berdasarkan metode.
- Total booking selesai.
- Total booking dibatalkan.
- Total booking dirujuk.
- Tabel riwayat layanan konseling.

Akses:

- Admin BKTS.

## 13. Data Requirements

### 13.1 Users

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| name | String | Nama pengguna |
| email | String | Email pengguna |
| password | String | Password terenkripsi |
| role | Enum | `super_admin`, `admin_bkts`, `konselor`, `mahasiswa` |
| status | Enum | `aktif`, `nonaktif` |
| created_at | Timestamp | Waktu data dibuat |
| updated_at | Timestamp | Waktu data diperbarui |

### 13.2 Mahasiswa

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| user_id | Integer | Relasi ke users |
| nim | String | Nomor induk mahasiswa |
| nama | String | Nama mahasiswa |
| program_studi | String | Program studi |
| angkatan | String | Angkatan |
| no_hp | String | Nomor HP |
| email | String | Email mahasiswa |

### 13.3 Konselor

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| user_id | Integer | Relasi ke users |
| nama | String | Nama konselor |
| bidang | String | Bidang atau spesialisasi umum |
| no_hp | String | Nomor HP |
| email | String | Email konselor |
| status | Enum | `aktif`, `nonaktif` |

### 13.4 Jadwal Konseling

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| konselor_id | Integer | Relasi ke konselor |
| tanggal | Date | Tanggal konseling |
| jam_mulai | Time | Jam mulai |
| jam_selesai | Time | Jam selesai |
| metode | Enum | `online`, `tatap_muka` |
| status | Enum | `tersedia`, `terpakai`, `tidak_aktif` |
| created_at | Timestamp | Waktu data dibuat |
| updated_at | Timestamp | Waktu data diperbarui |

### 13.5 Booking Konseling

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| kode_booking | String | Kode unik booking |
| mahasiswa_id | Integer | Relasi ke mahasiswa |
| jadwal_id | Integer | Relasi ke jadwal konseling |
| konselor_id | Integer | Relasi ke konselor |
| kategori | Enum | `akademik`, `non_akademik` |
| metode | Enum | `online`, `tatap_muka` |
| keluhan_awal | Text | Ringkasan masalah dari mahasiswa |
| status | Enum | `diajukan`, `dijadwalkan`, `selesai`, `dirujuk`, `dibatalkan` |
| link_meeting | String | Link meeting simulasi |
| alasan_pembatalan | Text | Alasan jika booking dibatalkan |
| created_at | Timestamp | Waktu pengajuan dibuat |
| updated_at | Timestamp | Waktu data diperbarui |

### 13.6 Catatan Konseling

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| booking_id | Integer | Relasi ke booking konseling |
| konselor_id | Integer | Relasi ke konselor |
| catatan_hasil | Text | Catatan rahasia hasil konseling |
| rekomendasi | Text | Rekomendasi tindak lanjut |
| created_at | Timestamp | Waktu catatan dibuat |
| updated_at | Timestamp | Waktu catatan diperbarui |

### 13.7 Rujukan

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| booking_id | Integer | Relasi ke booking konseling |
| tujuan_rujukan | String | Tujuan rujukan |
| alasan_rujukan | Text | Alasan rujukan |
| ringkasan_tindak_lanjut | Text | Ringkasan tindak lanjut |
| dibuat_oleh | Integer | User yang membuat rujukan |
| created_at | Timestamp | Waktu rujukan dibuat |

### 13.8 Notifikasi Simulasi

| Field | Tipe Data | Keterangan |
| --- | --- | --- |
| id | Integer | Primary key |
| booking_id | Integer | Relasi ke booking |
| penerima_id | Integer | User penerima simulasi |
| jenis | String | Jenis notifikasi |
| pesan | Text | Isi pesan simulasi |
| channel | Enum | `sistem`, `whatsapp_simulasi`, `email_simulasi` |
| status | Enum | `tercatat`, `gagal_simulasi` |
| created_at | Timestamp | Waktu notifikasi dicatat |

## 14. Notification & Link Meeting Simulation

### 14.1 Notification Simulation

Notifikasi simulasi adalah fitur pencatatan bahwa sistem seolah-olah mengirim pemberitahuan kepada pengguna. Pada MVP, notifikasi tidak benar-benar dikirim melalui WhatsApp atau Email.

Jenis notifikasi simulasi:

| Kondisi | Penerima | Contoh Pesan |
| --- | --- | --- |
| Pengajuan dibuat | Mahasiswa | Pengajuan konseling berhasil dibuat dan menunggu verifikasi Admin BKTS. |
| Pengajuan disetujui | Mahasiswa, Konselor | Booking konseling telah dijadwalkan. |
| Booking dibatalkan | Mahasiswa, Konselor | Booking konseling telah dibatalkan oleh Admin BKTS. |
| Booking selesai | Mahasiswa | Sesi konseling telah selesai. |
| Booking dirujuk | Admin BKTS | Mahasiswa membutuhkan tindak lanjut rujukan. |

### 14.2 Link Meeting Simulation

Link meeting simulasi dibuat hanya jika:

- Metode konseling adalah online.
- Booking sudah disetujui oleh Admin BKTS.
- Status booking berubah menjadi `Dijadwalkan`.

Format link meeting simulasi:

```text
meet.mock/{kode_booking}
zoom.mock/{kode_booking}
```

Contoh:

```text
meet.mock/BKTS-2026-0001
```

Link meeting simulasi dapat dilihat oleh:

- Mahasiswa yang membuat booking.
- Konselor yang ditugaskan.
- Admin BKTS.

## 15. Reporting Requirements

Sistem menyediakan laporan dasar untuk Admin BKTS.

Laporan yang tersedia:

- Jumlah seluruh pengajuan konseling.
- Jumlah booking berdasarkan status:
  - `Diajukan`
  - `Dijadwalkan`
  - `Selesai`
  - `Dirujuk`
  - `Dibatalkan`
- Jumlah booking berdasarkan kategori:
  - Akademik
  - Non-Akademik
- Jumlah booking berdasarkan metode:
  - Online
  - Tatap muka
- Jumlah booking selesai.
- Jumlah booking dibatalkan.
- Jumlah mahasiswa yang dirujuk.
- Riwayat layanan konseling secara umum.

Filter laporan MVP:

- Periode tanggal.
- Status booking.
- Kategori konseling.
- Metode konseling.
- Konselor.

Output laporan MVP:

- Ringkasan angka.
- Tabel data.
- Tampilan dashboard sederhana.

Export laporan seperti PDF atau Excel tidak wajib pada MVP.

## 16. Access Control

| Fitur | Super Admin | Admin BKTS | Konselor | Mahasiswa |
| --- | --- | --- | --- | --- |
| Login | Ya | Ya | Ya | Ya |
| Dashboard | Ya | Ya | Ya | Ya |
| Manajemen pengguna | Ya | Tidak | Tidak | Tidak |
| Manajemen data master | Ya | Tidak | Tidak | Tidak |
| Manajemen jadwal | Tidak | Ya | Tidak | Tidak |
| Membuat pengajuan konseling | Tidak | Tidak | Tidak | Ya |
| Melihat pengajuan sendiri | Tidak | Tidak | Tidak | Ya |
| Melihat semua pengajuan | Tidak | Ya | Tidak | Tidak |
| Verifikasi pengajuan | Tidak | Ya | Tidak | Tidak |
| Membatalkan booking | Tidak | Ya | Tidak | Tidak |
| Melihat jadwal yang ditugaskan | Tidak | Tidak | Ya | Tidak |
| Melihat detail booking yang ditugaskan | Tidak | Ya | Ya | Terbatas |
| Mengisi catatan konseling | Tidak | Tidak | Ya | Tidak |
| Melihat catatan konseling | Tidak | Ya | Ya, jika ditugaskan | Tidak |
| Mencatat rujukan | Tidak | Ya | Ya | Tidak |
| Melihat link meeting simulasi | Tidak | Ya | Ya, jika ditugaskan | Ya, jika miliknya |
| Melihat notifikasi simulasi | Tidak | Ya | Terbatas | Terbatas |
| Melihat laporan dasar | Tidak | Ya | Tidak | Tidak |

## 17. Acceptance Criteria

### 17.1 Authentication

- Pengguna dapat login menggunakan akun yang valid.
- Pengguna tidak dapat login jika password salah.
- Pengguna diarahkan ke dashboard sesuai role.
- Pengguna tidak dapat mengakses halaman di luar hak akses role-nya.

### 17.2 Mahasiswa

- Mahasiswa dapat membuka form pengajuan konseling.
- Mahasiswa dapat memilih kategori akademik atau non-akademik.
- Mahasiswa dapat memilih metode online atau tatap muka.
- Mahasiswa dapat memilih jadwal yang tersedia.
- Mahasiswa dapat mengisi keluhan awal.
- Mahasiswa dapat mengirim pengajuan.
- Sistem menyimpan pengajuan dengan status `Diajukan`.
- Mahasiswa dapat melihat status pengajuan.
- Mahasiswa dapat melihat riwayat pengajuan.
- Mahasiswa tidak dapat melihat catatan rahasia konselor.
- Mahasiswa dapat melihat link meeting simulasi jika booking online sudah disetujui.

### 17.3 Admin BKTS

- Admin BKTS dapat membuat jadwal konseling.
- Admin BKTS dapat menentukan konselor pada jadwal.
- Jadwal yang dibuat dapat dipilih oleh mahasiswa.
- Admin BKTS dapat melihat daftar pengajuan konseling.
- Admin BKTS dapat membuka detail pengajuan.
- Admin BKTS dapat menyetujui pengajuan.
- Saat pengajuan disetujui, status berubah menjadi `Dijadwalkan`.
- Admin BKTS dapat membatalkan pengajuan.
- Saat pengajuan dibatalkan, status berubah menjadi `Dibatalkan`.
- Saat booking dibatalkan, jadwal kembali tersedia.
- Admin BKTS dapat melihat laporan dasar.
- Admin BKTS dapat melihat catatan konseling.
- Admin BKTS dapat melihat data rujukan.

### 17.4 Konselor

- Konselor dapat melihat jadwal yang ditugaskan kepadanya.
- Konselor tidak dapat melihat jadwal konselor lain.
- Konselor dapat membuka detail pengajuan yang ditugaskan.
- Konselor dapat mengisi catatan hasil konseling.
- Konselor dapat mengisi rekomendasi tindak lanjut.
- Konselor dapat menandai booking sebagai `Selesai`.
- Konselor dapat menandai booking sebagai `Dirujuk`.
- Konselor dapat melihat riwayat sesi yang pernah ditangani.

### 17.5 Super Admin

- Super Admin dapat menambah akun pengguna.
- Super Admin dapat mengubah data akun pengguna.
- Super Admin dapat menonaktifkan akun pengguna.
- Super Admin dapat menentukan role pengguna.
- Super Admin dapat mengelola data master dasar.
- Super Admin tidak perlu menangani proses verifikasi konseling harian.

### 17.6 Notifikasi dan Link Meeting Simulasi

- Sistem mencatat notifikasi simulasi saat pengajuan dibuat.
- Sistem mencatat notifikasi simulasi saat pengajuan disetujui.
- Sistem mencatat notifikasi simulasi saat booking dibatalkan.
- Sistem mencatat notifikasi simulasi saat booking selesai.
- Sistem mencatat notifikasi simulasi saat booking dirujuk.
- Sistem membuat link meeting simulasi untuk booking online yang disetujui.
- Sistem tidak membuat link meeting untuk booking tatap muka.

## 18. Non-Functional Requirements

### 18.1 Usability

- Sistem harus mudah digunakan oleh mahasiswa.
- Form pengajuan konseling harus sederhana dan tidak terlalu panjang.
- Status booking harus mudah dipahami.
- Navigasi menu harus sesuai role.

### 18.2 Security

- Password pengguna harus disimpan dalam bentuk terenkripsi.
- Sistem harus menerapkan autentikasi login.
- Sistem harus menerapkan authorization berdasarkan role.
- Mahasiswa tidak boleh mengakses catatan rahasia konselor.
- Konselor hanya boleh mengakses booking yang ditugaskan kepadanya.
- Data pribadi mahasiswa harus dibatasi aksesnya.

### 18.3 Performance

- Halaman utama sistem harus dapat dimuat dengan waktu yang wajar.
- Proses pengajuan konseling harus dapat disimpan tanpa delay berlebihan.
- Laporan dasar harus dapat ditampilkan berdasarkan data booking yang tersedia.

### 18.4 Reliability

- Sistem harus mencegah satu jadwal dipilih oleh lebih dari satu mahasiswa.
- Sistem harus menjaga konsistensi status booking.
- Sistem harus menjaga konsistensi status jadwal.
- Sistem harus menampilkan pesan error jika proses gagal.

### 18.5 Maintainability

- Struktur fitur harus dipisahkan berdasarkan role.
- Struktur database harus mendukung pengembangan lanjutan.
- Kode program harus mudah dipahami dan dikelola.
- Penamaan tabel, field, dan route harus konsisten.

### 18.6 Compatibility

- Sistem berbasis web.
- Sistem dapat diakses melalui browser modern.
- Sistem responsif untuk tampilan laptop dan mobile browser dasar.

## 19. Assumptions & Constraints

### 19.1 Assumptions

- Mahasiswa melakukan pengajuan konseling secara mandiri melalui sistem.
- Semua pengajuan diverifikasi oleh Admin BKTS.
- Jadwal konseling sudah dibuat oleh Admin BKTS sebelum mahasiswa melakukan pengajuan.
- Konselor ditentukan oleh Admin BKTS saat membuat jadwal.
- Sistem hanya memiliki 4 role utama.
- Notifikasi hanya berupa simulasi.
- Link meeting hanya berupa simulasi.
- Rujukan hanya dicatat sebagai tindak lanjut manual.
- Tidak ada dashboard untuk Prodi, Psikolog, BAP, BKS, atau unit lain.
- Sistem digunakan untuk membantu administrasi layanan konseling, bukan menggantikan peran konselor profesional.

### 19.2 Constraints

- MVP tidak menggunakan integrasi pihak ketiga asli.
- MVP tidak menggunakan WhatsApp API asli.
- MVP tidak menggunakan Google Calendar asli.
- MVP tidak membuat Google Meet atau Zoom asli.
- MVP tidak menyediakan fitur chat real-time.
- MVP tidak menyediakan reschedule mandiri oleh mahasiswa.
- MVP tidak menyediakan audit trail lengkap.
- MVP tidak melakukan diagnosis psikologis otomatis.
- MVP hanya berfokus pada proses booking dan manajemen layanan konseling dasar.
- Scope harus tetap sederhana agar realistis untuk project pemweb.

## 20. Future Enhancements

Fitur yang dapat dikembangkan setelah MVP:

- Integrasi Fonnte WhatsApp API untuk notifikasi asli.
- Integrasi email asli.
- Integrasi Google Calendar.
- Generate Google Meet otomatis.
- Integrasi Zoom.
- Reminder otomatis H-1 sebelum jadwal konseling.
- Reschedule mandiri oleh mahasiswa.
- Jalur proaktif BKTS berdasarkan data mahasiswa bermasalah.
- Deteksi mahasiswa bermasalah berdasarkan IPK, absensi, atau status akademik.
- Dashboard Prodi.
- Dashboard Psikolog.
- Dashboard unit terkait.
- Audit trail perubahan status.
- Riwayat perubahan status detail.
- Export laporan ke PDF atau Excel.
- Laporan lanjutan berdasarkan periode.
- Integrasi SIAKAD asli.
- Import data mahasiswa dari sistem akademik.
- Chat atau pesan internal antara mahasiswa dan Admin BKTS.
- Aplikasi mobile Android atau iOS.
