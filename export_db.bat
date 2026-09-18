@echo off
setlocal enabledelayedexpansion
title FYP Management Portal - Export Database

:: Ensure working directory is the script's directory
cd /d "%~dp0"

echo =====================================================================
echo           FYP MANAGEMENT PORTAL - EXPORT DATABASE
echo =====================================================================
echo.
echo This utility will dump the current live 'fyp_management' database
echo into 'fyp_management.sql'.
echo.

set "DUMP_EXE="
where mysqldump >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    for /f "tokens=*" %%a in ('where mysqldump') do (
        if not defined DUMP_EXE set "DUMP_EXE=%%a"
    )
) else (
    if exist "C:\xampp\mysql\bin\mysqldump.exe" set "DUMP_EXE=C:\xampp\mysql\bin\mysqldump.exe"
    if exist "D:\xampp\mysql\bin\mysqldump.exe" set "DUMP_EXE=D:\xampp\mysql\bin\mysqldump.exe"
    if exist "D:\apps\xampp\xampp\mysql\bin\mysqldump.exe" set "DUMP_EXE=D:\apps\xampp\xampp\mysql\bin\mysqldump.exe"
    if not defined DUMP_EXE (
        for /d %%I in ("C:\laragon\bin\mysql\mysql-*") do (
            if exist "%%I\bin\mysqldump.exe" set "DUMP_EXE=%%I\bin\mysqldump.exe"
        )
    )
)

if not defined DUMP_EXE (
    echo [ERROR] mysqldump.exe was not found in PATH or standard XAMPP paths.
    echo Please export via phpMyAdmin at http://localhost/phpmyadmin or add MySQL bin to PATH.
    pause
    exit /b 1
)

set /p "DB_USER=Enter MySQL User [default 'root']: "
if "!DB_USER!"=="" set "DB_USER=root"
set /p "DB_PASS=Enter MySQL Password [press Enter if none]: "

echo [*] Exporting database to 'fyp_management.sql'...
if "!DB_PASS!"=="" (
    "!DUMP_EXE!" -u !DB_USER! --databases fyp_management --routines --triggers --default-character-set=utf8mb4 --result-file="fyp_management.sql"
) else (
    "!DUMP_EXE!" -u !DB_USER! -p!DB_PASS! --databases fyp_management --routines --triggers --default-character-set=utf8mb4 --result-file="fyp_management.sql"
)

if !ERRORLEVEL! EQU 0 (
    echo.
    echo [OK] 'fyp_management.sql' has been updated with the latest live database!
) else (
    echo.
    echo [ERROR] Export failed. Make sure MySQL is running and your credentials are correct.
)

pause
