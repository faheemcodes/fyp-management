@echo off
setlocal enabledelayedexpansion
title FYP Management Portal - Running Server

:: Ensure working directory is the script's directory
cd /d "%~dp0"

echo =====================================================================
echo                     FYP MANAGEMENT PORTAL
echo =====================================================================
echo.

where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\xampp\php\php.exe" (
        set "PATH=C:\xampp\php;!PATH!"
    ) else if exist "D:\xampp\php\php.exe" (
        set "PATH=D:\xampp\php;!PATH!"
    ) else if exist "D:\apps\xampp\xampp\php\php.exe" (
        set "PATH=D:\apps\xampp\xampp\php;!PATH!"
    ) else if exist "C:\laragon\bin\php" (
        for /d %%I in ("C:\laragon\bin\php\php-*") do (
            if exist "%%I\php.exe" set "PATH=%%I;!PATH!"
        )
    )
)

where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP was not found in system PATH or common XAMPP folders.
    echo Please run 'setup.bat' first or install XAMPP.
    echo.
    pause
    exit /b 1
)

echo [*] Opening browser at http://localhost:8000 ...
start http://localhost:8000

echo [*] FYP Management Portal is running on http://127.0.0.1:8000
echo [!] Keep this window open while using the application.
echo [!] Press Ctrl+C anytime to stop the server.
echo =====================================================================
echo.

php -S 127.0.0.1:8000 -t public
