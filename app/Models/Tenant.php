<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    protected static function booted(): void
    {
        static::created(function (Tenant $tenant) {
            // Hanya aktif di environment local & OS Windows
            if (app()->environment('local') && PHP_OS_FAMILY === 'Windows') {
                $domain = $tenant->slug . '.saas.test';
                
                // Script Powershell untuk inject file hosts
                $script = <<<PS
\$hostsPath = "\$env:windir\System32\drivers\etc\hosts"
\$entry = "127.0.0.1 \$domain"
if ((Get-Content \$hostsPath -Raw) -notmatch [regex]::Escape(\$entry)) {
    Add-Content -Path \$hostsPath -Value "`r`n\$entry"
}
PS;
                // Encode script ke Base64 (Syarat wajib untuk injeksi PowerShell `-EncodedCommand` agar stabil)
                $encoded = base64_encode(mb_convert_encoding($script, 'UTF-16LE', 'UTF-8'));
                
                // Eksekusi trigger UAC window secara asynchronous agar web tidak block
                pclose(popen("start /b powershell -Command \"Start-Process powershell -ArgumentList '-NoProfile -EncodedCommand {$encoded}' -Verb RunAs -WindowStyle Hidden\"", "r"));
            }
        });
    }
}
