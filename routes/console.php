<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('saas:register-domain {name?}', function (?string $name = null) {
    if (!$name) {
        $name = $this->ask('Masukkan nama domain atau subdomain (contoh: nama-tenant atau saas.test)');
    }

    if (!$name) {
        $this->error('Nama tidak boleh kosong.');
        return;
    }

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    
    // Jika input hanya subdomain, otomatis tambahkan base domain
    $domain = str_contains($name, '.') ? $name : "{$name}.{$baseDomain}";

    if (PHP_OS_FAMILY !== 'Windows') {
        $this->error("OS tidak didukung. Silakan tambahkan '127.0.0.1 {$domain}' ke /etc/hosts Anda secara manual.");
        return;
    }

    $this->info("Memproses registrasi domain lokal: {$domain}");

    $script = <<<PS
\$hostsPath = "\$env:windir\System32\drivers\etc\hosts"
\$entry = "127.0.0.1 $domain"
if ((Get-Content \$hostsPath -Raw) -notmatch [regex]::Escape(\$entry)) {
    Add-Content -Path \$hostsPath -Value "`r`n\$entry"
    Write-Host "Sukses: Domain $domain terdaftar!" -ForegroundColor Green
} else {
    Write-Host "Info: Domain $domain sudah terdaftar sebelumnya." -ForegroundColor Yellow
}
Start-Sleep -Seconds 3
PS;

    $encoded = base64_encode(mb_convert_encoding($script, 'UTF-16LE', 'UTF-8'));
    pclose(popen("start powershell -Command \"Start-Process powershell -ArgumentList '-NoProfile -EncodedCommand {$encoded}' -Verb RunAs\"", "r"));

    $this->info("Prompt Administrator (UAC) akan segera muncul. Silakan tekan 'Yes' untuk mengizinkan injeksi ke file hosts.");
})->purpose('Injeksi pendaftaran domain/subdomain ke file local Windows (hosts)');
