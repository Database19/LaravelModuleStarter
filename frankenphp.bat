@echo off
REM FrankenPHP Laravel Helper Script for Windows Batch
REM Usage: frankenphp.bat [command]

setlocal enabledelayedexpansion

if "%1"=="" goto :help
if "%1"=="help" goto :help
if "%1"=="start" goto :start
if "%1"=="stop" goto :stop
if "%1"=="restart" goto :restart
if "%1"=="status" goto :status
if "%1"=="install" goto :install
if "%1"=="docker-up" goto :docker_up
if "%1"=="docker-down" goto :docker_down
if "%1"=="docker-logs" goto :docker_logs
if "%1"=="start-binary" goto :start_binary
if "%1"=="start-octane" goto :start_octane
if "%1"=="start-docker" goto :docker_up
if "%1"=="watch" goto :watch

:help
echo.
echo ========================================
echo  FrankenPHP Laravel Helper for Windows
echo ========================================
echo.
echo 🚨 EMERGENCY NOTICE: Some methods disabled due to hanging issues!
echo.
echo ✅ SAFE COMMANDS:
echo   docker         Start with Docker (RECOMMENDED)
echo   binary         Start with FrankenPHP binary
echo   install        Install FrankenPHP binary
echo   docker-up      Start with Docker Compose
echo   docker-down    Stop Docker Compose
echo   docker-logs    Show Docker logs
echo   stop           Stop FrankenPHP server
echo   status         Check FrankenPHP status
echo   help           Show this help message
echo.
echo 🚫 DISABLED COMMANDS (Dangerous - can hang system):
echo   start-octane   ❌ DISABLED - Can cause system hang
echo   watch          ❌ DISABLED - Can cause system hang
echo.
echo 🆘 EMERGENCY:
echo   If system hangs, run: emergency-stop.bat
echo.
echo 💡 RECOMMENDED USAGE:
echo   frankenphp.bat docker
echo.
echo 📖 More info: WINDOWS-TROUBLESHOOTING.md
echo.
goto :eof

:install
echo Installing FrankenPHP for Windows...
curl -fsSL https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-windows-x86_64.exe -o frankenphp.exe
if !errorlevel! == 0 (
    echo FrankenPHP installed successfully!
    echo You can now use 'frankenphp.bat start' to start the server.
) else (
    echo Failed to download FrankenPHP
)
goto :eof

:start
echo Starting FrankenPHP server...

REM Check if .env exists
if not exist ".env" (
    echo Creating .env file from .env.example...
    copy ".env.example" ".env"
)

REM Install dependencies if needed
if not exist "vendor" (
    echo Installing Composer dependencies...
    composer install
)

REM Generate app key if needed
findstr /C:"APP_KEY=" .env > nul
if !errorlevel! neq 0 (
    echo Generating application key...
    php artisan key:generate
)

REM Set Octane server to FrankenPHP
findstr /C:"OCTANE_SERVER=frankenphp" .env > nul
if !errorlevel! neq 0 (
    echo OCTANE_SERVER=frankenphp >> .env
    echo Added OCTANE_SERVER=frankenphp to .env
)

echo Starting FrankenPHP on http://localhost:8000
echo.
echo ============================================
echo  FrankenPHP Startup Options for Windows
echo ============================================
echo 1. FrankenPHP Binary (Best Performance)
echo 2. Docker Compose (Most Reliable)
echo 3. Laravel Octane (May have signal issues)
echo.
echo Checking available options...
echo.

if exist "frankenphp.exe" (
    echo [✓] FrankenPHP binary found. Using binary method...
    echo Starting server with FrankenPHP binary on http://localhost:8000
    echo Press Ctrl+C to stop the server.
    echo.
    frankenphp.exe run --config Caddyfile
) else (
    echo [!] FrankenPHP binary not found. Installing...
    call :install
    echo.
    if exist "frankenphp.exe" (
        echo [✓] FrankenPHP binary installed successfully!
        echo Starting server with FrankenPHP binary on http://localhost:8000
        echo Press Ctrl+C to stop the server.
        echo.
        frankenphp.exe run --config Caddyfile
    ) else (
        echo [!] FrankenPHP binary installation failed.
        echo.
        echo ALTERNATIVE OPTIONS:
        echo 1. Use Docker: frankenphp.bat docker
        echo 2. Use Laravel default: php artisan serve
        echo 3. Read troubleshooting: WINDOWS-TROUBLESHOOTING.md
        echo.
        echo 🚨 WARNING: Octane method disabled due to hanging issues
        echo Using Laravel default server instead...
        echo.
        echo Starting Laravel development server...
        php artisan serve --port=8000
    )
)
goto :eof

:stop
echo Stopping FrankenPHP server...
taskkill /F /IM frankenphp.exe 2>nul
if !errorlevel! == 0 (
    echo FrankenPHP stopped
) else (
    echo FrankenPHP was not running
    php artisan octane:stop 2>nul
)
goto :eof

:restart
call :stop
timeout /t 2 /nobreak > nul
call :start
goto :eof

:status
echo Checking FrankenPHP status...
tasklist /FI "IMAGENAME eq frankenphp.exe" | find /I "frankenphp.exe" > nul
if !errorlevel! == 0 (
    echo FrankenPHP is running
    tasklist /FI "IMAGENAME eq frankenphp.exe"
) else (
    echo FrankenPHP is not running
)

REM Check if port 8000 is in use
netstat -an | find ":8000" > nul
if !errorlevel! == 0 (
    echo Port 8000 is in use
) else (
    echo Port 8000 is free
)
goto :eof

:docker_up
echo Starting Laravel ERP with Docker Compose and FrankenPHP...
docker-compose up -d --build
if !errorlevel! == 0 (
    echo Services started successfully!
    echo Application: http://localhost:8000
    echo phpMyAdmin: http://localhost:8080
    echo Use 'docker-compose logs -f app' to see application logs
) else (
    echo Failed to start Docker services
)
goto :eof

:docker_down
echo Stopping Docker Compose services...
docker-compose down
if !errorlevel! == 0 (
    echo Services stopped
) else (
    echo Failed to stop Docker services
)
goto :eof

:docker_logs
echo Showing Docker logs for Laravel application...
docker-compose logs -f app
goto :eof

:start_binary
echo Starting FrankenPHP with binary...
if not exist "frankenphp.exe" (
    echo FrankenPHP binary not found. Installing...
    call :install
)
if exist "frankenphp.exe" (
    echo FrankenPHP binary found. Starting server on http://localhost:8000
    frankenphp.exe run --config Caddyfile
) else (
    echo Failed to install FrankenPHP binary. Please try manual installation.
)
goto :eof

:start_octane
:start-octane
echo.
echo ========================================
echo  🚨 WARNING: OCTANE METHOD DISABLED
echo ========================================
echo.
echo ❌ The Octane method has been temporarily disabled due to hanging issues
echo � This method can cause system hang and require restart
echo.
echo ✅ Safe alternatives:
echo   1. frankenphp.bat docker    (Recommended)
echo   2. frankenphp.bat binary    (Alternative)
echo   3. php artisan serve        (Laravel default)
echo.
echo 💡 Use Docker method for best FrankenPHP experience:
echo    frankenphp.bat docker
echo.
pause
goto :eof:watch
echo.
echo ========================================
echo  Starting FrankenPHP with File Watching
echo ========================================
echo.

echo ========================================
echo  � WARNING: WATCH METHOD DISABLED
echo ========================================
echo.
echo ❌ The watch method has been temporarily disabled due to hanging issues
echo � This method can cause system hang and require restart
echo.
echo ✅ Safe alternatives:
echo   1. frankenphp.bat docker    (Recommended)
echo   2. frankenphp.bat binary    (Alternative)
echo   3. php artisan serve        (Laravel default)
echo.
echo 💡 For file watching, use Docker with volume mounting:
echo    frankenphp.bat docker
echo.
pause
goto :eof
