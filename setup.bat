@echo off
echo =========================================
echo Setting up FYP Management System
echo =========================================
echo.

echo Checking for Composer...
composer --version >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed or not in your PATH.
    echo Please download and install Composer from https://getcomposer.org/
    echo.
    pause
    exit /b 1
)

echo.
echo Installing PHP dependencies...
call composer install

echo.
echo =========================================
echo Setup completed successfully!
echo =========================================
echo.
echo To start the server locally, run: 
echo php -S 0.0.0.0:8000 -t public
echo.
pause
