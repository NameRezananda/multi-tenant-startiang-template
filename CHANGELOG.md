# Changelog & Progress Dokumentasi
**AutoHub SaaS v2**

Dokumentasi ini berisi rekapitulasi riwayat pengerjaan, implementasi fitur, pembaruan infrastruktur, serta perbaikan *bug/error* yang telah selesai dikerjakan pada proyek ini.

---

### 1. Automasi Multi-Tenant Showroom Setup & Blueprint
- **Core Tenancy Setup:** Mengimplementasikan *row-level multi-tenancy* menggunakan Filament v5 pada database tunggal (PostgreSQL).
- **Subdomain Routing:** Melakukan konfigurasi pada `ShowroomPanelProvider` untuk mendukung login dan *routing* multi-panel berdasarkan *wildcard* subdomain. Konfigurasi ini secara efektif mengubah akses halaman dari yang semula `autohub.test/showroom` menjadi spesifik per tenant, contohnya: `nama-dealer.autohub.test`.
- **Model Relationships:** Mengimplementasikan fungsi `HasTenants` pada model `User` serta tabel pivot `tenant_user` agar relasi dapat menampung arsitektur *Many-to-Many* (Satu user dapat memanajemen banyak showroom/cabang).
- **Otomatisasi Inject Tenant ID (`BelongsToTenant`):** Membuat dan memasang fungsi Trait otomatis untuk setiap form penambahan mobil/aset baru. Sistem kini otomatis menyisipkan `tenant_id` dari panel yang aktif saat ini ke database tanpa membutuhkan pengisian manual (`BelongsToTenant.php`).
- **Otomasi Local Workflow:** Menerapkan proses automasi update file `setup-hosts.ps1` dan berkas *hosts* Windows (opsional) menggunakan model observer sehingga sinkron ketika terdapat pendaftaran domain tenant baru pada *local environment*.

### 2. Peningkatan User Interface (UI) & Fitur Interaktif
- **Sidebar Search Filter:** Mengimplementasikan fitur "pencarian cepat" (*search filter*) di dalam menu navigasi *sidebar* menggunakan Alpine.js. Menu dapat diseleksi dan dicari memakai input teks (melalui fungsionalitas direktif `x-show` yang dinamis).
- **Toast Notifications:** Membangun *component reusable* untuk memunculkan notifikasi Toast. Notifikasi diimplementasikan di global layout, guna memberikan konfirmasi (feedback) langsung saat form Master Data beres disubmit atau jika terjadi interaksi sukses lainnya.
- **Refactoring Komponen Form Modal:** Mengorganisir form modal penambahan Master Data (Brands, Types, Models, dsb.) dengan meletakkan HTML modal di dalam *blade directive* `@push('modals')` agar penataan *z-index*, performa, tata letak dan keresponsifan UI tetap rapi, berada pada *layer* depan tanpa terhalang elemen lain.

### 3. Fungsionalitas Reporting dan Layout Percetakan (Print Layouts)
- **Modul Cetak Laporan Penuh:** Mengintegrasikan fitur Print Modal untuk laporan esensial (Sales, Work Orders, Stock).
- **Adaptasi Controller:** Memodifikasi `ReportController` agar melayani data secara terpisah apabila permintaan diarahkan untuk perenderan *print preview* dengan file PDF.
- **Standarisasi Layout A4:** Memperbaiki sistem *Print Preview* dan struktur CSS (*Page Break*/Margin), mengatasi masalah teks yang sering terpotong *(cut-off)* sehingga laporan siap dicetak presisi mengikuti kertas A4.

### 4. Technical Hotfix & Routing 
- **Resolusi "Route [login] not defined":** Menyempurnakan penanganan *Middleware* & arsitektur rute di aplikasi multi-tenant, mendefinisikan rute *login parameter redirect* sehingga admin pusat maupun user subdomain tenant diarahkan pada form *Auth* yang benar sesuai *domain origin* masing-masing.
- **Bug Fix Database Migrations:** Meneyelesaikan konfilk saat command `migrate:central` dijalankan akibat servis DBMS (PostgreSQL) menolak koneksi `SQLSTATE[08006] connection refused` dengan memastikan konfigurasi .env sesuai `ServBay` port & host, serta membetulkan pemanggilan `runUp()` *protected method* pada perintah migrasi spesifik untuk data tenant.
- **Database Seeding Setup:** Menjalankan seeder (`DatabaseSeeder.php`) agar super admin *default* langsung memiliki properti tenansi awal, untuk testing login panel-showroom langsung bekerja.
