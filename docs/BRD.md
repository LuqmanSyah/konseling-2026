# Business Requirement Document

## Sistem Booking dan Manajemen Layanan Konseling Mahasiswa

## 1. Ringkasan Eksekutif

Sistem Booking dan Manajemen Layanan Konseling Mahasiswa adalah aplikasi berbasis web yang digunakan untuk membantu mahasiswa mengajukan layanan konseling secara mandiri.

Pada versi MVP, sistem hanya berfokus pada jalur self-service mahasiswa, yaitu mahasiswa dapat login, mengajukan konseling, memilih kategori konseling, memilih metode konseling, memilih jadwal yang tersedia, dan memantau status pengajuannya.

Admin BKTS berperan dalam mengelola jadwal, memverifikasi pengajuan, mengatur status booking, mencatat notifikasi simulasi, dan memantau layanan konseling. Konselor berperan dalam melihat jadwal yang ditugaskan, melakukan sesi konseling, mencatat hasil konseling, serta memberikan rekomendasi atau rujukan apabila diperlukan.

Sistem hanya memiliki 4 role utama, yaitu Super Admin, Admin BKTS, Konselor, dan Mahasiswa. Role lain seperti Prodi, Psikolog, BAP, BKS, atau unit terkait tidak memiliki dashboard khusus pada tahap MVP. Jika diperlukan rujukan, rujukan hanya dicatat oleh sistem sebagai tindak lanjut manual yang dikelola oleh Admin BKTS.

Pada tahap MVP, sistem tidak melakukan integrasi asli dengan WhatsApp API, Google Calendar, Google Meet, Zoom, atau Email. Fitur notifikasi dan link meeting dibuat dalam bentuk simulasi agar proses bisnis tetap dapat berjalan dan dapat didemonstrasikan.

## 2. Latar Belakang

Layanan konseling mahasiswa dibutuhkan untuk membantu mahasiswa menghadapi permasalahan akademik maupun non-akademik selama masa studi. Permasalahan yang dapat ditangani dapat berupa kesulitan belajar, masalah akademik, tekanan perkuliahan, masalah pribadi, masalah keluarga, atau kebutuhan pendampingan lainnya.

Dalam proses manual, pengajuan konseling dapat dilakukan melalui WhatsApp, telepon, email, atau komunikasi langsung dengan pihak BKTS. Proses seperti ini berpotensi menyebabkan pengajuan tidak terdokumentasi dengan baik, jadwal sulit dipantau, dan riwayat konseling sulit ditemukan kembali.

Oleh karena itu, dibutuhkan sistem berbasis web yang dapat membantu proses pengajuan, penjadwalan, pencatatan hasil konseling, dan tindak lanjut secara lebih terpusat dan terstruktur.

## 3. Permasalahan Bisnis

Permasalahan yang ingin diselesaikan oleh sistem ini adalah:

- Mahasiswa belum memiliki satu tempat terpusat untuk mengajukan konseling.
- Proses pengajuan konseling masih dapat tersebar melalui komunikasi manual.
- Jadwal konseling berisiko tidak terdokumentasi dengan rapi.
- Mahasiswa sulit memantau status pengajuan konseling.
- Admin BKTS membutuhkan cara yang lebih mudah untuk memverifikasi dan mengelola pengajuan.
- Konselor membutuhkan daftar sesi konseling yang ditugaskan secara jelas.
- Catatan hasil konseling perlu disimpan secara terstruktur.
- Rujukan atau tindak lanjut konseling perlu dicatat agar dapat dipantau oleh Admin BKTS.
- Notifikasi jadwal dan perubahan status masih berisiko terlewat jika dilakukan sepenuhnya secara manual.

## 4. Tujuan Bisnis

Tujuan dari sistem ini adalah:

- Mempermudah mahasiswa dalam mengajukan layanan konseling secara mandiri.
- Memusatkan proses pengajuan, penjadwalan, dan pemantauan status konseling.
- Membantu Admin BKTS mengelola pengajuan konseling mahasiswa.
- Membantu Admin BKTS mengelola jadwal konseling.
- Membantu konselor melihat jadwal konseling yang ditugaskan.
- Membantu konselor mencatat hasil konseling dan rekomendasi tindak lanjut.
- Menyediakan pencatatan rujukan apabila mahasiswa membutuhkan tindak lanjut.
- Menjaga kerahasiaan catatan konseling melalui pembatasan hak akses.
- Menyediakan laporan dasar untuk memantau layanan konseling.
- Menyediakan dasar pengembangan lanjutan untuk integrasi layanan pihak ketiga.

## 5. Ruang Lingkup MVP

### 5.1 Termasuk dalam MVP

Fitur bisnis yang termasuk dalam MVP adalah:

- Login pengguna berdasarkan role.
- Pengelolaan akun oleh Super Admin.
- Mahasiswa mengajukan konseling secara mandiri.
- Mahasiswa memilih kategori konseling.
- Mahasiswa memilih metode konseling.
- Mahasiswa memilih jadwal yang tersedia.
- Admin BKTS mengelola jadwal konseling.
- Admin BKTS memverifikasi pengajuan konseling.
- Admin BKTS mengubah status booking.
- Sistem menyediakan link meeting simulasi untuk konseling online.
- Sistem mencatat notifikasi simulasi.
- Konselor melihat jadwal konseling yang ditugaskan.
- Konselor mengisi catatan hasil konseling.
- Konselor memberikan rekomendasi tindak lanjut.
- Konselor atau Admin BKTS dapat mencatat rujukan.
- Mahasiswa dapat melihat status dan riwayat pengajuan konseling.
- Admin BKTS dapat melihat laporan dasar layanan konseling.

### 5.2 Tidak Termasuk dalam MVP

Fitur berikut tidak termasuk dalam MVP:

- Jalur proaktif BKTS berdasarkan data mahasiswa bermasalah.
- Deteksi mahasiswa bermasalah berdasarkan IPK, absensi, atau status aktif.
- Dashboard Prodi.
- Dashboard Psikolog.
- Dashboard BAP, BKS, atau unit lain.
- Integrasi WhatsApp API asli.
- Integrasi Google Calendar asli.
- Generate Google Meet asli.
- Integrasi Zoom asli.
- Pengiriman email asli.
- Reschedule mandiri oleh mahasiswa.
- Audit trail lengkap.
- Riwayat perubahan status detail.
- Diagnosis psikologis otomatis.
- Chat real-time antara mahasiswa dan konselor.
- Aplikasi mobile Android atau iOS.
- Integrasi SIAKAD asli.

## 6. Stakeholder dan Role Pengguna

| Role | Peran Bisnis |
| --- | --- |
| Super Admin | Mengelola akun pengguna, role, data master, dan pengaturan dasar sistem. |
| Admin BKTS | Mengelola operasional konseling, seperti jadwal, pengajuan, verifikasi, booking, notifikasi simulasi, rujukan, dan laporan dasar. |
| Konselor | Melihat jadwal konseling yang ditugaskan, mencatat hasil konseling, memberi rekomendasi, dan mencatat rujukan jika diperlukan. |
| Mahasiswa | Mengajukan konseling, memilih jadwal, melihat status pengajuan, melihat link meeting simulasi, dan melihat riwayat konseling. |

## 7. Proses Bisnis Utama

### 7.1 Alur Pengajuan Konseling oleh Mahasiswa

1. Mahasiswa login ke sistem.
2. Mahasiswa membuka menu pengajuan konseling.
3. Mahasiswa memilih kategori konseling:
   - Akademik.
   - Non-Akademik.
4. Mahasiswa memilih metode konseling:
   - Online.
   - Tatap muka.
5. Mahasiswa memilih jadwal konseling yang tersedia.
6. Mahasiswa mengisi keluhan awal atau ringkasan masalah.
7. Mahasiswa mengirim pengajuan.
8. Sistem mencatat pengajuan dengan status awal `Diajukan`.
9. Mahasiswa dapat melihat status pengajuan pada dashboard.

### 7.2 Alur Verifikasi oleh Admin BKTS

1. Admin BKTS login ke sistem.
2. Admin BKTS melihat daftar pengajuan konseling mahasiswa.
3. Admin BKTS memeriksa detail pengajuan.
4. Admin BKTS dapat memproses pengajuan dengan salah satu tindakan:
   - Menyetujui pengajuan.
   - Membatalkan pengajuan jika diperlukan.
5. Jika pengajuan disetujui, status booking berubah menjadi `Dijadwalkan`.
6. Jika metode konseling adalah online, sistem menyediakan link meeting simulasi.
7. Sistem mencatat notifikasi simulasi untuk mahasiswa dan konselor.
8. Mahasiswa dapat melihat status terbaru, jadwal, dan link meeting simulasi jika tersedia.

### 7.3 Alur Pengelolaan Jadwal oleh Admin BKTS

1. Admin BKTS login ke sistem.
2. Admin BKTS membuka menu jadwal konseling.
3. Admin BKTS membuat jadwal konseling.
4. Admin BKTS menentukan hari dalam satu minggu, jam, metode, dan konselor untuk jadwal tersebut.
5. Jadwal yang tersedia dapat dipilih oleh mahasiswa saat mengajukan konseling.
6. Jika jadwal sudah dipilih oleh mahasiswa, jadwal tersebut tidak dapat dipilih oleh mahasiswa lain.
7. Jika booking dibatalkan, jadwal dapat tersedia kembali.

### 7.4 Alur Pelaksanaan Konseling oleh Konselor

1. Konselor login ke sistem.
2. Konselor melihat daftar jadwal konseling yang ditugaskan.
3. Konselor membuka detail pengajuan mahasiswa.
4. Konselor melaksanakan sesi konseling sesuai jadwal.
5. Setelah sesi selesai, konselor mengisi catatan hasil konseling.
6. Konselor mengisi rekomendasi tindak lanjut.
7. Jika tidak memerlukan tindak lanjut lanjutan, booking ditandai sebagai `Selesai`.
8. Jika membutuhkan penanganan lanjutan, booking dapat ditandai sebagai `Dirujuk`.

### 7.5 Alur Rujukan atau Tindak Lanjut

1. Konselor atau Admin BKTS menentukan bahwa mahasiswa membutuhkan tindak lanjut.
2. Tujuan rujukan dapat berupa:
   - Psikolog.
   - Prodi.
   - Unit akademik.
   - Unit keuangan.
   - Unit lain yang relevan.
3. Sistem menyimpan ringkasan rujukan.
4. Status booking dapat berubah menjadi `Dirujuk`.
5. Rujukan tidak membuat dashboard baru untuk pihak tujuan rujukan.
6. Admin BKTS menindaklanjuti rujukan secara manual di luar sistem jika diperlukan.
7. Mahasiswa tidak dapat melihat catatan rahasia konseling, tetapi dapat melihat status umum booking.

### 7.6 Alur Pembatalan Booking

1. Admin BKTS membuka detail booking.
2. Admin BKTS memilih tindakan pembatalan.
3. Admin BKTS mengisi alasan pembatalan.
4. Status booking berubah menjadi `Dibatalkan`.
5. Jadwal yang sebelumnya dipilih dapat tersedia kembali untuk pengajuan lain.
6. Sistem mencatat notifikasi simulasi pembatalan.

### 7.7 Alur Riwayat Konseling

1. Mahasiswa dapat melihat daftar pengajuan konseling yang pernah dibuat.
2. Mahasiswa dapat melihat status setiap pengajuan.
3. Mahasiswa dapat melihat jadwal dan metode konseling.
4. Mahasiswa tidak dapat melihat catatan rahasia konselor.
5. Konselor dapat melihat riwayat sesi konseling yang pernah ditangani.
6. Admin BKTS dapat melihat riwayat layanan konseling untuk kebutuhan monitoring.

## 8. Status Booking

Status booking pada MVP terdiri dari:

| Status | Keterangan Bisnis |
| --- | --- |
| Diajukan | Mahasiswa sudah mengirim pengajuan konseling dan menunggu verifikasi Admin BKTS. |
| Dijadwalkan | Pengajuan sudah diverifikasi dan sesi konseling sudah memiliki jadwal. |
| Selesai | Sesi konseling sudah dilaksanakan dan catatan hasil konseling sudah diisi. |
| Dirujuk | Mahasiswa membutuhkan tindak lanjut ke psikolog, Prodi, unit akademik, unit keuangan, atau unit terkait lain. |
| Dibatalkan | Pengajuan atau booking dibatalkan oleh Admin BKTS. |

## 9. Aturan Bisnis

Aturan bisnis sistem adalah:

- Sistem hanya menggunakan jalur self-service mahasiswa pada tahap MVP.
- Mahasiswa hanya dapat mengajukan konseling setelah login.
- Pengajuan baru selalu memiliki status awal `Diajukan`.
- Admin BKTS bertanggung jawab memverifikasi pengajuan.
- Booking yang disetujui berubah menjadi `Dijadwalkan`.
- Booking yang dibatalkan berubah menjadi `Dibatalkan`.
- Jika booking dibatalkan, jadwal dapat digunakan kembali.
- Konselor hanya menangani booking yang ditugaskan kepadanya.
- Konselor dapat mengisi catatan hasil konseling setelah sesi dilakukan.
- Catatan pribadi konseling hanya dapat dilihat oleh Admin BKTS dan Konselor yang berwenang.
- Mahasiswa tidak dapat melihat catatan rahasia konselor.
- Rujukan hanya dicatat sebagai tindak lanjut manual, bukan membuat dashboard baru untuk pihak tujuan rujukan.
- Notifikasi pada MVP hanya berupa simulasi yang tercatat di sistem.
- Link meeting online pada MVP hanya berupa link simulasi.
- Super Admin mengelola akun dan data master, bukan proses konseling harian.
- Admin BKTS mengelola proses operasional konseling harian.

## 10. Kebutuhan Bisnis per Role

### 10.1 Super Admin

Super Admin membutuhkan sistem untuk:

- Mengelola akun pengguna.
- Mengelola role pengguna.
- Mengelola data Admin BKTS.
- Mengelola data Konselor.
- Mengelola data Mahasiswa.
- Mengelola pengaturan dasar sistem.
- Memastikan data master sistem tersedia untuk proses operasional.

### 10.2 Admin BKTS

Admin BKTS membutuhkan sistem untuk:

- Melihat pengajuan konseling mahasiswa.
- Memverifikasi pengajuan konseling.
- Mengelola jadwal konseling.
- Menentukan konselor pada jadwal konseling.
- Mengubah status booking.
- Membatalkan booking jika diperlukan.
- Melihat riwayat layanan konseling.
- Melihat laporan dasar.
- Mencatat notifikasi simulasi.
- Melihat dan menindaklanjuti rujukan dari konselor.

### 10.3 Konselor

Konselor membutuhkan sistem untuk:

- Melihat jadwal konseling yang ditugaskan.
- Melihat ringkasan pengajuan mahasiswa.
- Melaksanakan sesi konseling.
- Mengisi catatan hasil konseling.
- Memberikan rekomendasi tindak lanjut.
- Menandai sesi sebagai selesai.
- Menandai mahasiswa perlu dirujuk jika diperlukan.
- Melihat riwayat sesi konseling yang pernah ditangani.

### 10.4 Mahasiswa

Mahasiswa membutuhkan sistem untuk:

- Login ke sistem.
- Mengajukan konseling dengan mudah.
- Memilih kategori konseling.
- Memilih metode konseling.
- Memilih jadwal yang tersedia.
- Mengisi keluhan awal atau ringkasan masalah.
- Melihat status pengajuan.
- Melihat jadwal konseling yang sudah disetujui.
- Melihat link meeting simulasi jika konseling online.
- Melihat riwayat pengajuan konseling.

## 11. Notifikasi dan Link Meeting Simulasi

Pada tahap MVP, sistem menyediakan simulasi notifikasi dan link meeting.

### 11.1 Notifikasi Simulasi

Notifikasi simulasi digunakan untuk mencatat bahwa sistem seolah-olah telah mengirim pemberitahuan kepada pengguna.

Notifikasi simulasi dapat digunakan pada kondisi:

- Pengajuan berhasil dibuat.
- Pengajuan disetujui oleh Admin BKTS.
- Booking dibatalkan.
- Booking selesai.
- Booking dirujuk.

Notifikasi tidak benar-benar dikirim melalui WhatsApp atau email pada tahap MVP.

### 11.2 Link Meeting Simulasi

Jika mahasiswa memilih metode konseling online, sistem menyediakan link meeting simulasi setelah pengajuan disetujui.

Link meeting simulasi digunakan untuk kebutuhan demo dan pencatatan proses bisnis.

Contoh konsep link meeting simulasi:

```text
meet.mock/kode-booking
```

atau:

```text
zoom.mock/kode-booking
```

## 12. Kebutuhan Laporan Bisnis

Sistem menyediakan laporan dasar berupa:

- Jumlah pengajuan konseling.
- Jumlah booking berdasarkan status.
- Jumlah booking berdasarkan kategori.
- Jumlah booking berdasarkan metode konseling.
- Jumlah booking yang selesai.
- Jumlah booking yang dibatalkan.
- Jumlah mahasiswa yang dirujuk.
- Riwayat layanan konseling secara umum.

## 13. Asumsi Bisnis

Asumsi yang digunakan pada MVP:

- Mahasiswa mengajukan konseling secara mandiri melalui sistem.
- Semua pengajuan diproses oleh Admin BKTS.
- Jadwal konseling tersedia sebelum mahasiswa melakukan pengajuan.
- Konselor ditentukan pada jadwal konseling oleh Admin BKTS.
- Notifikasi belum dikirim melalui layanan eksternal.
- Link meeting belum dibuat melalui layanan eksternal.
- Rujukan hanya dicatat sebagai tindak lanjut manual.
- Tidak ada dashboard khusus untuk Prodi, Psikolog, BAP, BKS, atau unit lain.
- Sistem digunakan untuk membantu administrasi layanan konseling, bukan menggantikan peran profesional konselor atau psikolog.
- Data pribadi dan catatan konseling harus dibatasi aksesnya.

## 14. Risiko Bisnis dan Mitigasi

| Risiko | Mitigasi |
| --- | --- |
| Mahasiswa bingung saat mengajukan konseling | Form pengajuan dibuat sederhana dengan kategori terbatas. |
| Mahasiswa memilih jadwal yang salah | Admin BKTS dapat membatalkan booking jika diperlukan. |
| Jadwal sudah dipilih tetapi booking dibatalkan | Jadwal dapat tersedia kembali. |
| Mahasiswa tidak melihat perubahan status | Sistem menampilkan status booking pada dashboard mahasiswa. |
| Catatan konseling bocor ke pihak tidak berwenang | Akses catatan dibatasi berdasarkan role pengguna. |
| Mahasiswa melihat catatan rahasia konselor | Mahasiswa hanya dapat melihat status umum dan riwayat pengajuan. |
| Rujukan tidak ditindaklanjuti | Admin BKTS dapat melihat catatan rujukan dan menindaklanjuti secara manual. |
| Notifikasi tidak benar-benar terkirim | MVP menggunakan notifikasi simulasi yang tercatat dalam sistem. |
| Link meeting tidak benar-benar dibuat dari Google Meet/Zoom | MVP menggunakan link meeting simulasi untuk kebutuhan demo. |
| Scope sistem melebar terlalu besar | Role dibatasi menjadi 4 dan jalur layanan hanya self-service. |

## 15. Kriteria Penerimaan Bisnis

Sistem dianggap memenuhi kebutuhan bisnis MVP apabila:

- Super Admin dapat mengelola akun dan role pengguna.
- Mahasiswa dapat login ke sistem.
- Mahasiswa dapat mengajukan konseling secara mandiri.
- Mahasiswa dapat memilih kategori akademik atau non-akademik.
- Mahasiswa dapat memilih metode online atau tatap muka.
- Mahasiswa dapat memilih jadwal yang tersedia.
- Pengajuan mahasiswa tersimpan dengan status `Diajukan`.
- Admin BKTS dapat melihat pengajuan konseling.
- Admin BKTS dapat memverifikasi pengajuan.
- Admin BKTS dapat mengubah status menjadi `Dijadwalkan`.
- Sistem dapat menampilkan link meeting simulasi untuk konseling online.
- Sistem dapat mencatat notifikasi simulasi.
- Konselor dapat melihat jadwal konseling yang ditugaskan.
- Konselor dapat mengisi catatan hasil konseling.
- Booking dapat ditandai sebagai `Selesai`.
- Booking dapat ditandai sebagai `Dirujuk`.
- Booking dapat ditandai sebagai `Dibatalkan`.
- Mahasiswa dapat melihat status dan riwayat konseling.
- Admin BKTS dapat melihat laporan dasar layanan konseling.
- Mahasiswa tidak dapat melihat catatan rahasia konselor.
- Sistem menerapkan pembatasan akses berdasarkan 4 role utama.

## 16. Pengembangan Lanjutan

Fitur yang dapat dikembangkan setelah MVP selesai:

- Jalur proaktif BKTS berdasarkan data mahasiswa bermasalah.
- Dashboard Prodi.
- Dashboard Psikolog.
- Integrasi Fonnte WhatsApp API.
- Integrasi email asli.
- Integrasi Google Calendar.
- Generate Google Meet otomatis.
- Integrasi Zoom.
- Reminder otomatis sebelum jadwal konseling.
- Reschedule oleh mahasiswa.
- Audit trail perubahan status.
- Riwayat perubahan status detail.
- Laporan lanjutan berdasarkan periode.
- Integrasi SIAKAD asli.
- Import data mahasiswa dari sistem akademik.

## 17. Kesimpulan

BRD versi ini memfokuskan sistem pada jalur self-service mahasiswa dengan 4 role utama, yaitu Super Admin, Admin BKTS, Konselor, dan Mahasiswa.

Dengan membatasi role dan proses bisnis, sistem menjadi lebih sederhana, lebih mudah dipahami, dan lebih realistis untuk diimplementasikan pada project pemweb. Sistem tetap mencakup proses bisnis utama layanan konseling, yaitu pengajuan konseling oleh mahasiswa, verifikasi oleh Admin BKTS, pengelolaan jadwal, pelaksanaan sesi oleh konselor, pencatatan hasil konseling, rujukan sederhana, notifikasi simulasi, link meeting simulasi, dan laporan dasar.

BRD ini sudah lebih aman untuk diturunkan ke PRD, ERD, implementasi coding, dan laporan akhir karena scope-nya lebih jelas dan tidak terlalu melebar.
