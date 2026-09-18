@echo off
setlocal enabledelayedexpansion
title FYP Management Portal - Setup Wizard

:: Ensure working directory is the script's directory
cd /d "%~dp0"

cls
echo =====================================================================
echo              FYP MANAGEMENT PORTAL - SETUP WIZARD
echo =====================================================================
echo.
echo Welcome! This script will prepare the project for first-time use:
echo  1. Locate or verify PHP installation
echo  2. Prepare directories [uploads, sessions]
echo  3. Generate configuration files [database, mail]
echo  4. Install/verify PHP dependencies [Composer, vendor]
echo  5. Import the updated database [fyp_management.sql]
echo =====================================================================
echo.

:: -----------------------------------------------------------------------
:: Step 1: Detect PHP
:: -----------------------------------------------------------------------
echo [Step 1/5] Checking PHP installation...
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [*] PHP is not in system PATH. Checking common paths...
    if exist "C:\xampp\php\php.exe" (
        set "PATH=C:\xampp\php;!PATH!"
        echo [*] Located PHP in C:\xampp\php
    ) else if exist "D:\xampp\php\php.exe" (
        set "PATH=D:\xampp\php;!PATH!"
        echo [*] Located PHP in D:\xampp\php
    ) else if exist "D:\apps\xampp\xampp\php\php.exe" (
        set "PATH=D:\apps\xampp\xampp\php;!PATH!"
        echo [*] Located PHP in D:\apps\xampp\xampp\php
    ) else if exist "C:\laragon\bin\php" (
        for /d %%I in ("C:\laragon\bin\php\php-*") do (
            if exist "%%I\php.exe" (
                set "PATH=%%I;!PATH!"
                echo [*] Located PHP in %%I
            )
        )
    )
)

where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP executable was not found.
    echo Please install XAMPP from https://www.apachefriends.org or PHP 8.1+
    echo and ensure 'php.exe' is added to your system PATH.
    echo.
    pause
    exit /b 1
)

for /f "tokens=*" %%v in ('php -r "echo PHP_VERSION;"') do set "PHP_VER=%%v"
echo [OK] PHP is ready - Version: !PHP_VER!
echo.

:: -----------------------------------------------------------------------
:: Step 2: Create Required Directories
:: -----------------------------------------------------------------------
echo [Step 2/5] Checking directory structure...
if not exist "sessions" mkdir "sessions"
if not exist "public\uploads" mkdir "public\uploads"
if not exist "public\uploads\avatars" mkdir "public\uploads\avatars"
if not exist "public\uploads\proposals" mkdir "public\uploads\proposals"
if not exist "public\uploads\notices" mkdir "public\uploads\notices"
if not exist "public\uploads\submissions" mkdir "public\uploads\submissions"
echo [OK] Storage directories verified.
echo.

:: -----------------------------------------------------------------------
:: Step 3: Setup Configuration Files
:: -----------------------------------------------------------------------
echo [Step 3/5] Setting up configuration files...
if not exist "config\database.php" (
    if exist "config\database.example.php" (
        copy /Y "config\database.example.php" "config\database.php" >nul
        echo [*] Created config\database.php from template [Default: 127.0.0.1, user: root, password: '']
    ) else (
        echo [WARN] config\database.example.php not found!
    )
) else (
    echo [*] config\database.php already exists.
)

if not exist "config\mail.php" (
    if exist "config\mail.example.php" (
        copy /Y "config\mail.example.php" "config\mail.php" >nul
        echo [*] Created config\mail.php from template.
    )
) else (
    echo [*] config\mail.php already exists.
)
echo [OK] Configuration files ready.
echo.

:: -----------------------------------------------------------------------
:: Step 4: Composer & Dependencies
:: -----------------------------------------------------------------------
echo [Step 4/5] Checking dependencies [Composer and vendor]...
set "HAS_COMPOSER=0"
where composer >nul 2>&1
if %ERRORLEVEL% EQU 0 set "HAS_COMPOSER=1"

if "!HAS_COMPOSER!"=="1" (
    echo [*] Running 'composer install'...
    call composer install --no-interaction
    echo [OK] Dependencies verified with Composer.
) else (
    if exist "vendor\autoload.php" (
        echo [OK] Pre-installed 'vendor' directory detected. Skipping composer install.
    ) else (
        echo [*] Composer not detected globally. Downloading composer.phar locally...
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" >nul 2>&1
        if exist "composer-setup.php" (
            php composer-setup.php --quiet
            del composer-setup.php >nul 2>&1
            if exist "composer.phar" (
                echo [*] Running local composer.phar...
                php composer.phar install --no-interaction
                echo [OK] Dependencies installed successfully via composer.phar.
            )
        ) else (
            echo [WARN] Could not automatically download composer.phar.
            echo If you experience issues, please install Composer from https://getcomposer.org/
        )
    )
)
echo.

:: -----------------------------------------------------------------------
:: Step 5: Database Setup & Import
:: -----------------------------------------------------------------------
echo [Step 5/5] Checking MySQL and importing database...
set "MYSQL_EXE="
where mysql >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    for /f "tokens=*" %%a in ('where mysql') do (
        if not defined MYSQL_EXE set "MYSQL_EXE=%%a"
    )
) else (
    if exist "C:\xampp\mysql\bin\mysql.exe" set "MYSQL_EXE=C:\xampp\mysql\bin\mysql.exe"
    if exist "D:\xampp\mysql\bin\mysql.exe" set "MYSQL_EXE=D:\xampp\mysql\bin\mysql.exe"
    if exist "D:\apps\xampp\xampp\mysql\bin\mysql.exe" set "MYSQL_EXE=D:\apps\xampp\xampp\mysql\bin\mysql.exe"
    if not defined MYSQL_EXE (
        for /d %%I in ("C:\laragon\bin\mysql\mysql-*") do (
            if exist "%%I\bin\mysql.exe" set "MYSQL_EXE=%%I\bin\mysql.exe"
        )
    )
)

if defined MYSQL_EXE (
    echo [*] MySQL client found: "!MYSQL_EXE!"
    echo.
    set /p "DO_IMPORT=Do you want to import/update 'fyp_management.sql' now? [Y/N, default Y]: "
    if "!DO_IMPORT!"=="" set "DO_IMPORT=Y"
    if /i "!DO_IMPORT!"=="Y" (
        set /p "DB_USER=Enter MySQL User [default 'root']: "
        if "!DB_USER!"=="" set "DB_USER=root"
        set /p "DB_PASS=Enter MySQL Password [press Enter if none]: "
        
        echo [*] Importing 'fyp_management.sql' into MySQL...
        if "!DB_PASS!"=="" (
            "!MYSQL_EXE!" -u !DB_USER! -e "source fyp_management.sql"
        ) else (
            "!MYSQL_EXE!" -u !DB_USER! -p!DB_PASS! -e "source fyp_management.sql"
        )
        
        if !ERRORLEVEL! EQU 0 (
            echo [OK] Database 'fyp_management' imported successfully!
        ) else (
            echo [WARN] Automatic database import returned an error.
            echo Please make sure your MySQL server is currently running in XAMPP.
            echo You can also import 'fyp_management.sql' manually via phpMyAdmin:
            echo  - Open http://localhost/phpmyadmin
            echo  - Go to 'Import' and select 'fyp_management.sql'
        )
    )
) else (
    echo [INFO] MySQL command-line tool was not found in common locations.
    echo [*] Please ensure MySQL is running in XAMPP, then import manually:
    echo     1. Open http://localhost/phpmyadmin in your browser.
    echo     2. Click 'Import' tab.
    echo     3. Choose 'fyp_management.sql' from this project directory and click 'Go'.
)

echo.
echo =====================================================================
echo                   SETUP COMPLETE - PROJECT READY!
echo =====================================================================
echo.
echo To run the project anytime in the future, just double-click:
echo    start.bat
echo.
set /p "START_NOW=Do you want to start the project server right now? [Y/N, default Y]: "
if "!START_NOW!"=="" set "START_NOW=Y"
if /i "!START_NOW!"=="Y" (
    echo [*] Launching browser at http://localhost:8000 ...
    start http://localhost:8000
    echo [*] Starting PHP built-in server...
    echo [!] Press Ctrl+C anytime to stop the server.
    echo.
    php -S 127.0.0.1:8000 -t public
)

pause
