$ErrorActionPreference = 'Stop'
$hostsPath = "$env:windir\System32\drivers\etc\hosts"
$entries = @(
    "127.0.0.1 jakarta.autohub.test",
    "127.0.0.1 bandung.autohub.test"
)

try {
    $hostsContent = Get-Content $hostsPath -Raw
    $toAdd = @()

    foreach ($entry in $entries) {
        if (-not ($hostsContent -match [regex]::Escape($entry))) {
            $toAdd += $entry
        }
    }

    if ($toAdd.Count -gt 0) {
        $newContent = "`r`n" + ($toAdd -join "`r`n")
        Add-Content -Path $hostsPath -Value $newContent
        Write-Host "Berhasil menambahkan domain ke file hosts Windows!" -ForegroundColor Green
    } else {
        Write-Host "Domain sudah ada di dalam file hosts, tidak ada perubahan." -ForegroundColor Yellow
    }
} catch {
    Write-Host "Gagal memodifikasi file hosts. Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nJendela ini akan menutup otomatis dalam 5 detik..."
Start-Sleep -Seconds 5
