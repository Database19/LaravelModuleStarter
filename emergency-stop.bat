@echo off
setlocal enabledelayedexpansion

echo.
echo ========================================
echo  🚨 EMERGENCY STOP SCRIPT
echo ========================================
echo.

echo Killing all PHP processes...
taskkill /F /IM php.exe 2>nul
if !errorlevel! == 0 (
    echo ✅ PHP processes terminated
) else (
    echo ❌ No PHP processes found or failed to terminate
)

echo.
echo Killing all FrankenPHP processes...
taskkill /F /IM frankenphp.exe 2>nul
if !errorlevel! == 0 (
    echo ✅ FrankenPHP processes terminated
) else (
    echo ❌ No FrankenPHP processes found or failed to terminate
)

echo.
echo Killing all Artisan processes...
wmic process where "CommandLine like '%%artisan%%'" delete 2>nul
echo ✅ Artisan processes cleanup attempted

echo.
echo Killing all Octane processes...
wmic process where "CommandLine like '%%octane%%'" delete 2>nul
echo ✅ Octane processes cleanup attempted

echo.
echo Stopping Docker containers...
docker-compose down 2>nul
if !errorlevel! == 0 (
    echo ✅ Docker containers stopped
) else (
    echo ❌ Docker not available or no containers running
)

echo.
echo ========================================
echo  🛠️ CLEANUP COMPLETE
echo ========================================
echo.
echo All FrankenPHP/Laravel processes should now be stopped.
echo If your system is still hanging, you may need to:
echo.
echo 1. Open Task Manager (Ctrl+Shift+Esc)
echo 2. Look for any remaining php.exe or frankenphp.exe processes
echo 3. End those processes manually
echo.
echo Safe restart methods:
echo   frankenphp.bat docker    (Recommended)
echo   php artisan serve        (Laravel default)
echo.
pause
