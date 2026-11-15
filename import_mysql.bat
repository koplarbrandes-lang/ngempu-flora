@echo off
echo ===================================================
echo   Import Database 'adopsi' ke Laragon MySQL
echo ===================================================
echo.

set MYSQL_BIN=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe

if not exist "%MYSQL_BIN%" (
    set MYSQL_BIN=mysql
)

echo [1/2] Membuat database 'adopsi' jika belum ada...
"%MYSQL_BIN%" -u root -e "CREATE DATABASE IF NOT EXISTS adopsi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %ERRORLEVEL% neq 0 (
    echo.
    echo [ERROR] Gagal terhubung ke MySQL!
    echo Pastikan MySQL di Laragon sudah dinyalakan (Klik 'Start All' di Laragon).
    pause
    exit /b 1
)

echo [2/2] Mengimpor tabel dan data adopsi.sql...
"%MYSQL_BIN%" -u root adopsi < "%~dp0adopsi.sql"

if %ERRORLEVEL% equ 0 (
    echo.
    echo ===================================================
    echo   SUKSES! Database 'adopsi' berhasil diimpor.
    echo   Anda dapat melihatnya di HeidiSQL atau phpMyAdmin.
    echo ===================================================
) else (
    echo.
    echo [ERROR] Gagal mengimpor adopsi.sql.
)

pause
