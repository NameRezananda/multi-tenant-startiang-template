# Multi-Tenant SaaS Template

Template aplikasi multi-tenant berbasis Laravel dengan Filament v5, menggunakan database tunggal (PostgreSQL) dengan row-level security.

## Fitur Utama

- **Multi-Tenancy Row-Level:** Database tunggal dengan pemisahan data per tenant menggunakan `tenant_id`.
- **Subdomain Routing:** Panel aplikasi dapat diakses melalui subdomain (contoh: `tenant1.saas.test`).
- **Filament v5:** Menggunakan versi terbaru Filament dengan fitur-fitur modern.
- **Automasi Setup:** Skrip PowerShell untuk konfigurasi otomatis `hosts` dan *database*.

## Instalasi

### Prasyarat

- PHP 8.2+
- Composer
- PostgreSQL 14+
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd autohub-saas-v2
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```

   Edit file `.env` dengan konfigurasi database Anda:
   ```ini
   DB_CONNECTION=pgsql
   DB_HOST=localhost
   DB_PORT=5432
   DB_DATABASE=saas
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

4. **Generate Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrate Database**
   ```bash
   php artisan migrate
   ```

6. **Seed Database**
   ```bash
   php artisan db:seed
   ```

7. **Jalankan Server**
   ```bash
   php artisan serve
   ```

### Panduan Update (Setelah `git pull`)

Jika Anda sudah menginstal template ini dan baru saja melakukan `git pull` untuk mengambil kode terbaru dari repositori, disarankan untuk menjalankan serangkaian perintah berikut agar repositori lokal tersinkronisasi:

```bash
# 1. Install & Update modul PHP terbaru
composer install

# 2. Install & Update modul frontend terbaru
npm install
npm run build

# 3. Eksekusi file migrasi baru (jika ada pembaruan struktur DB)
php artisan migrate

# 4. (Opsional) Jalankan seeder khusus jika tim menambahkan data master/referensi baru
php artisan db:seed

# 5. Bersihkan seluruh file cache sistem
php artisan optimize:clear
```

## Konfigurasi Multi-Tenant

### 1. Tambah Tenant Baru

Akses panel admin di `http://saas.test/admin` (jika menggunakan Valet/ServBay) atau `http://localhost:8000/admin` (jika artisan serve) dan login menggunakan super admin:
- Email: `admin@saas.test`
- Password: `password`

Buat tenant baru dengan domain yang diinginkan (contoh: `jakarta`).

### 2. Automasi Setup (Windows)

Setelah membuat tenant, jalankan skrip PowerShell untuk update `hosts`:
```powershell
.\setup-hosts.ps1
```

Skrip ini akan menambahkan entri ke `hosts` file:
```
127.0.0.1 jakarta.saas.test
```

### 3. Akses Tenant Panel

Tenant panel dapat diakses melalui subdomain:
```
http://jakarta.saas.test/app
```

## Struktur Proyek

```
app/
├── Models/
│   ├── Tenant.php            # Model tenant
│   ├── User.php              # Model user dengan HasTenants trait
│   └── ...
├── Providers/
│   └── AppPanelProvider.php  # Konfigurasi panel aplikasi
├── Traits/
│   ├── BelongsToTenant.php   # Trait untuk otomatisasi tenant_id
│   └── HasTenants.php        # Trait untuk multi-tenancy
└── ...

config/
├── tenant.php                # Konfigurasi multi-tenancy
└── ...

database/
├── migrations/
│   ├── 2026_04_07_061342_create_tenants_table.php
│   └── 2026_04_07_061400_create_tenant_user_table.php
├── seeders/
│   └── DatabaseSeeder.php    # Seeder super admin
└── ...

public/
└── ...

resources/
├── Filament/
│   ├── Resources/
│   │   └── Tenants/
│   │       ├── RelationManagers/
│   │       │   └── UsersRelationManager.php
│   │       └── TenantsResource.php
│   └── ...
└── ...

setup-hosts.ps1               # Skrip automasi hosts
```

## Teknologi yang Digunakan

- **Framework:** Laravel 11
- **Admin Panel:** Filament v5
- **Database:** PostgreSQL
- **Authentication:** Laravel Sanctum
- **Routing:** Wildcard Subdomain Routing
- **Multi-Tenancy:** Row-Level Security

## Troubleshooting

### Error: "Route [login] not defined"

Pastikan Anda mengakses panel yang benar:
- Admin Panel: `http://localhost:8000/admin`
- Tenant Panel: `http://tenant.saas.test/app`

### Error: "Connection refused" pada PostgreSQL

Pastikan PostgreSQL berjalan dan konfigurasi di `.env` sudah benar:
```ini
DB_HOST=localhost
DB_PORT=5432
```

### Error: Filament assets tidak terload

Jalankan perintah berikut:
```bash
php artisan filament:assets
php artisan filament:publish --tag=filament-actions
```

## Lisensi

MIT License
