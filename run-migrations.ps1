# Script untuk menjalankan migration database DailyMoo
# Jalankan script ini di PowerShell

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "DailyMoo - Database Migration Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek apakah PHP tersedia
$phpPath = Get-Command php -ErrorAction SilentlyContinue

if (-not $phpPath) {
    Write-Host "PHP tidak ditemukan di PATH." -ForegroundColor Yellow
    Write-Host "Mencoba mencari PHP di Laragon..." -ForegroundColor Yellow
    
    # Coba path Laragon default
    $laragonPhpPaths = @(
        "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.0.0-Win32-vs16-x64\php.exe"
    )
    
    $phpFound = $false
    foreach ($path in $laragonPhpPaths) {
        if (Test-Path $path) {
            $env:Path = "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64;$env:Path"
            Write-Host "PHP ditemukan di: $path" -ForegroundColor Green
            $phpFound = $true
            break
        }
    }
    
    if (-not $phpFound) {
        Write-Host "ERROR: PHP tidak ditemukan!" -ForegroundColor Red
        Write-Host "Silakan install PHP atau tambahkan ke PATH." -ForegroundColor Red
        Write-Host ""
        Write-Host "Atau jalankan manual di terminal Laragon:" -ForegroundColor Yellow
        Write-Host "  php artisan migrate" -ForegroundColor White
        exit 1
    }
}

# Cek apakah file .env ada
if (-not (Test-Path ".env")) {
    Write-Host "ERROR: File .env tidak ditemukan!" -ForegroundColor Red
    Write-Host "Silakan copy .env.example ke .env dan konfigurasi database." -ForegroundColor Yellow
    exit 1
}

Write-Host "1. Mengecek status migration..." -ForegroundColor Cyan
php artisan migrate:status
Write-Host ""

$response = Read-Host "Apakah Anda ingin melanjutkan migration? (y/n)"
if ($response -ne "y" -and $response -ne "Y") {
    Write-Host "Migration dibatalkan." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "2. Menjalankan migration..." -ForegroundColor Cyan
php artisan migrate

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "Migration berhasil!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "3. Mengecek status migration setelah selesai..." -ForegroundColor Cyan
    php artisan migrate:status
    Write-Host ""
    Write-Host "Selanjutnya:" -ForegroundColor Yellow
    Write-Host "  - Buka phpMyAdmin untuk verifikasi tabel" -ForegroundColor White
    Write-Host "  - Jalankan: php artisan storage:link" -ForegroundColor White
} else {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Red
    Write-Host "Migration gagal!" -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
    Write-Host ""
    Write-Host "Silakan cek error di atas dan perbaiki." -ForegroundColor Yellow
    exit 1
}

