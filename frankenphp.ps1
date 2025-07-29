# FrankenPHP Laravel Helper Script for Windows PowerShell
# Usage: .\frankenphp.ps1 [command]

param(
    [Parameter(Position=0)]
    [string]$Command = "help"
)

# Function to print colored output
function Write-ColorOutput {
    param(
        [Parameter(Mandatory=$true)]
        [string]$Message,
        [Parameter(Mandatory=$false)]
        [string]$Color = "White"
    )

    switch ($Color) {
        "Red" { Write-Host $Message -ForegroundColor Red }
        "Green" { Write-Host $Message -ForegroundColor Green }
        "Yellow" { Write-Host $Message -ForegroundColor Yellow }
        "Blue" { Write-Host $Message -ForegroundColor Blue }
        "Cyan" { Write-Host $Message -ForegroundColor Cyan }
        default { Write-Host $Message }
    }
}

# Function to print usage
function Show-Usage {
    Write-Host "FrankenPHP Laravel Helper Script for Windows" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Usage: .\frankenphp.ps1 [command]" -ForegroundColor White
    Write-Host ""
    Write-Host "Commands:" -ForegroundColor White
    Write-Host "  start        Start FrankenPHP server" -ForegroundColor Gray
    Write-Host "  stop         Stop FrankenPHP server" -ForegroundColor Gray
    Write-Host "  restart      Restart FrankenPHP server" -ForegroundColor Gray
    Write-Host "  install      Install FrankenPHP binary" -ForegroundColor Gray
    Write-Host "  docker-up    Start with Docker Compose" -ForegroundColor Gray
    Write-Host "  docker-down  Stop Docker Compose" -ForegroundColor Gray
    Write-Host "  docker-logs  Show Docker logs" -ForegroundColor Gray
    Write-Host "  status       Check FrankenPHP status" -ForegroundColor Gray
    Write-Host "  help         Show this help message" -ForegroundColor Gray
}

# Function to check if FrankenPHP is installed
function Test-FrankenPHP {
    if (Get-Command frankenphp -ErrorAction SilentlyContinue) {
        return $true
    }

    if (Test-Path ".\frankenphp.exe") {
        return $true
    }

    Write-ColorOutput "FrankenPHP is not installed. Please run '.\frankenphp.ps1 install' first." "Red"
    return $false
}

# Function to install FrankenPHP
function Install-FrankenPHP {
    Write-ColorOutput "Installing FrankenPHP for Windows..." "Blue"

    try {
        # Download FrankenPHP for Windows
        $url = "https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-windows-x86_64.exe"
        $output = ".\frankenphp.exe"

        Write-ColorOutput "Downloading FrankenPHP..." "Yellow"
        Invoke-WebRequest -Uri $url -OutFile $output

        Write-ColorOutput "FrankenPHP installed successfully!" "Green"
        Write-ColorOutput "You can now use '.\frankenphp.ps1 start' to start the server." "Cyan"
    }
    catch {
        Write-ColorOutput "Failed to download FrankenPHP: $_" "Red"
        return $false
    }

    return $true
}

# Function to start FrankenPHP
function Start-FrankenPHP {
    if (-not (Test-FrankenPHP)) {
        return
    }

    Write-ColorOutput "Starting FrankenPHP server..." "Blue"

    # Ensure Laravel is ready
    if (-not (Test-Path ".env")) {
        Write-ColorOutput "Creating .env file from .env.example..." "Yellow"
        Copy-Item ".env.example" ".env"
    }

    # Install dependencies if needed
    if (-not (Test-Path "vendor")) {
        Write-ColorOutput "Installing Composer dependencies..." "Yellow"
        composer install
    }

    # Generate app key if needed
    $envContent = Get-Content ".env" -Raw
    if ($envContent -notmatch "APP_KEY=.+" -or $envContent -match "APP_KEY=$") {
        Write-ColorOutput "Generating application key..." "Yellow"
        php artisan key:generate
    }

    # Set Octane server to FrankenPHP
    if ($envContent -notmatch "OCTANE_SERVER=frankenphp") {
        Add-Content ".env" "OCTANE_SERVER=frankenphp"
        Write-ColorOutput "Added OCTANE_SERVER=frankenphp to .env" "Yellow"
    }

    # Start FrankenPHP
    Write-ColorOutput "Starting FrankenPHP on http://localhost:8000" "Green"

    if (Test-Path ".\frankenphp.exe") {
        .\frankenphp.exe run --config Caddyfile
    } else {
        frankenphp run --config Caddyfile
    }
}

# Function to stop FrankenPHP
function Stop-FrankenPHP {
    Write-ColorOutput "Stopping FrankenPHP server..." "Blue"

    try {
        Get-Process -Name "frankenphp" -ErrorAction Stop | Stop-Process -Force
        Write-ColorOutput "FrankenPHP stopped" "Green"
    }
    catch {
        Write-ColorOutput "FrankenPHP was not running" "Yellow"
    }
}

# Function to start with Docker
function Start-Docker {
    Write-ColorOutput "Starting Laravel ERP with Docker Compose and FrankenPHP..." "Blue"

    try {
        # Build and start containers
        docker-compose up -d --build

        Write-ColorOutput "Services started successfully!" "Green"
        Write-ColorOutput "Application: http://localhost:8000" "Blue"
        Write-ColorOutput "phpMyAdmin: http://localhost:8080" "Blue"
        Write-ColorOutput "Use 'docker-compose logs -f app' to see application logs" "Yellow"
    }
    catch {
        Write-ColorOutput "Failed to start Docker services: $_" "Red"
    }
}

# Function to stop Docker
function Stop-Docker {
    Write-ColorOutput "Stopping Docker Compose services..." "Blue"

    try {
        docker-compose down
        Write-ColorOutput "Services stopped" "Green"
    }
    catch {
        Write-ColorOutput "Failed to stop Docker services: $_" "Red"
    }
}

# Function to show Docker logs
function Show-DockerLogs {
    Write-ColorOutput "Showing Docker logs for Laravel application..." "Blue"
    docker-compose logs -f app
}

# Function to check status
function Test-Status {
    Write-ColorOutput "Checking FrankenPHP status..." "Blue"

    try {
        $process = Get-Process -Name "frankenphp" -ErrorAction Stop
        Write-ColorOutput "FrankenPHP is running" "Green"
        Write-ColorOutput "PID: $($process.Id)" "Blue"
    }
    catch {
        Write-ColorOutput "FrankenPHP is not running" "Red"
    }

    # Check if port 8000 is in use
    try {
        $connection = Test-NetConnection -ComputerName "localhost" -Port 8000 -InformationLevel Quiet
        if ($connection) {
            Write-ColorOutput "Port 8000 is in use" "Green"
        } else {
            Write-ColorOutput "Port 8000 is free" "Yellow"
        }
    }
    catch {
        Write-ColorOutput "Could not check port 8000" "Yellow"
    }
}

# Main script logic
switch ($Command.ToLower()) {
    "start" {
        Start-FrankenPHP
    }
    "stop" {
        Stop-FrankenPHP
    }
    "restart" {
        Stop-FrankenPHP
        Start-Sleep -Seconds 2
        Start-FrankenPHP
    }
    "install" {
        Install-FrankenPHP
    }
    "docker-up" {
        Start-Docker
    }
    "docker-down" {
        Stop-Docker
    }
    "docker-logs" {
        Show-DockerLogs
    }
    "status" {
        Test-Status
    }
    default {
        Show-Usage
    }
}
