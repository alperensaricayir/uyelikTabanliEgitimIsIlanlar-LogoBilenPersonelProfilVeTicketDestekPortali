@echo off
title 3S Grup Portal Otomatik Kurulum
color 0b

echo =================================================================
echo        3S Grup Portal - Tek Tikla Kurulum Aracina Hosgeldiniz    
echo =================================================================
echo.
echo Bu arac projenin farkli bir bilgisayarda kolayca calisabilmesi 
echo icin gereken tum (vendor, node_modules) paketleri internetten
echo indirip ayarlari otomatik yapacaktir.
echo.
echo GEREKSINIMLER (Lutfen kurulu oldugundan emin olun):
echo - PHP (Minimum 8.2)
echo - Composer
echo - Node.js (npm)
echo.
echo Kuruluma baslamak icin bir tusa basin veya iptal icin X'e basin.
pause

echo.
echo [1/6] Composer paketleri (PHP kutuphaneleri) indiriliyor...
echo Lutfen bekleyin, bu islem biraz surebilir...
call composer install

echo.
echo [2/6] NPM paketleri (Arayuz/JS kutuphaneleri) indiriliyor...
call npm install

echo.
echo [3/6] .env ayar dosyasi kontrol ediliyor...
if not exist .env (
    copy .env.example .env
    echo Ozel .env dosyasi basariyla olusturuldu.
) else (
    echo .env dosyasi halihazirda mevcut, dokunulmadi.
)

echo.
echo [4/6] Guvenlik anahtari (App Key) olusturuluyor...
call php artisan key:generate

echo.
echo [5/6] SQLite Veritabani hazirlaniyor...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo Yeni SQLite veritabani dosyasi olusturuldu.
) else (
    echo Mevcut SQLite veritabani bulundu, veriler korunacak.
)
call php artisan migrate --force

echo.
echo [6/6] Storage (Fotograf/Dosya) baglantilari yapiliyor...
call php artisan storage:link

echo.
echo =================================================================
echo                        KURULUM TAMAMLANDI!                       
echo =================================================================
echo Projeniz sisteminizde sorunsuz bir sekilde derlendi ve hazir.
echo.
echo Projeyi baslatmak icin terminal (CMD) uzerinden su iki komutu 
echo ayri pencerelerde calistirmalisiniz:
echo.
echo 1)  npm run dev
echo 2)  php artisan serve
echo.
echo -----------------------------------------------------------------
echo Cikmak icin bir tusa basin...
pause >nul
