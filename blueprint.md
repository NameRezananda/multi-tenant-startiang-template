# 🏎️ Project Blueprint: Multi-Tenant SaaS (v3.3)

**Concept:** SaaS Platform Template
**Architecture:** Row-Level Multi-tenancy (Single Database) with Many-to-Many User Access
**Tech Stack:** Laravel 13, Filament v5, PostgreSQL (ServBay), Livewire v3 (SPA Mode)

## 1. Arsitektur Terkini (Rencana)
**Multi-Panel System:** 
* **AdminPanelProvider (`/admin`):** Manajemen Global & Subscription.
* **AppPanelProvider (`/app`):** Operasional Tenant (Tenancy Enabled).

**Many-to-Many Tenancy:** User terhubung ke Tenant melalui tabel pivot `tenant_user`. Satu akun bisa mengelola banyak cabang/tenant dengan Native Tenant Switcher.

**Database Optimization:** PostgreSQL dengan fokus pada B-Tree Indexing untuk kolom `tenant_id`.

## 2. Progress Checklist (Status Aktual Saat Ini)
berdasarkan pengecekan kondisi source code terkini.

✅ **Phase 1: Core & Infrastructure**
- [x] Install Laravel 13 & Filament v5.
- [x] Konfigurasi PostgreSQL (ServBay) di `.env`.
- [x] Buat Model & Migration Tenant. *(Selesai - File Model `Tenant` dan Migration sudah dibuat)*
- [x] Buat Migration Pivot `tenant_user`. *(Selesai)*
- [x] Jalankan `php artisan migrate`. *(Selesai)*

✅ **Phase 2: Filament Multi-Panel Setup**
- [x] Generate `AdminPanelProvider`.
- [x] Generate `AppPanelProvider`.
- [x] Registrasi Provider di `bootstrap/providers.php`.
- [x] Implementasi Interface `HasTenants` pada model `User`. *(Selesai)*
- [x] Hubungkan model `User` dan `Tenant` via `BelongsToMany`. *(Selesai)*

## 3. Current Configuration Code (Review Kode Aktual)
Kondisi kode saat ini **SUDAH** mengikuti Tenancy Logic seperti pada blueprint awal.

**A. `AppPanelProvider.php`**
Kondisi aktual sudah memiliki pemanggilan `login()`, `tenant(...)`, dan `spa()`.
```php
// Status Aktual
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('app')
        ->path('app')
        ->login()
        ->tenant(\App\Models\Tenant::class, slugAttribute: 'slug')
        ->spa()
        ->colors([
            'primary' => Color::Amber,
        ])
        // ...
}
```

**B. `User.php` (Tenancy Logic)**
Kondisi aktual sudah ditambahkan implementasi `HasTenants` dari Filament maupun relasi ke `Tenant`.
```php
// Status Aktual
class User extends Authenticatable implements HasTenants
{
    use HasFactory, Notifiable;
    
    public function tenants(): BelongsToMany { ... }
    public function getTenants(Panel $panel): Collection { ... }
    public function canAccessTenant(Model $tenant): bool { ... }
}
```

## 4. Next Step: Siap Meluncurkan Aplikasi
Seluruh sistem yang dirancang di tahap dasar kini sudah terpasang rapi, dari infrastruktur database hingga provider panel.

**Langkah Anda Selanjutnya:**
1. Jalankan `php artisan serve` di terminal (atau via ServBay endpoint Anda).
2. Lanjutkan ke tahap **"The Grand Opening"** seperti yang direncanakan di bawah!

**5. "The Grand Opening" (Lanjutan Setelah Setup Selesai)**
Jika Poin 4 di atas sudah tuntas, langkah selanjutnya adalah:
- [x] **Database Seeding:** Membuat data dummy agar bisa login ke `/tenant` dan melihat pilihan tenant. *(Selesai - Admin `admin@saas.test` & Tenant tersimpan)*
- [x] **Wildcard Subdomain Setup:** Mengubah akses dari `saas.test/app` menjadi `nama-tenant.saas.test`. *(Selesai - Setup domain tenant pada Provider)*
- [x] **BelongsToTenant Trait:** Membuat Logic otomatis yang menyisipkan `tenant_id` secara otomatis ketika menambah mobil tanpa input manual. *(Selesai - Trait `BelongsToTenant` telah dibuat)*
