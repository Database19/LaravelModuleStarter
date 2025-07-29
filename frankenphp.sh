#!/bin/bash

# FrankenPHP Laravel Helper Script
# Usage: ./frankenphp.sh [command]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Print colored output
print_color() {
    printf "${!1}%s${NC}\n" "$2"
}

# Print usage
print_usage() {
    echo "FrankenPHP Laravel Helper Script"
    echo ""
    echo "Usage: $0 [command]"
    echo ""
    echo "Commands:"
    echo "  start        Start FrankenPHP server"
    echo "  stop         Stop FrankenPHP server"
    echo "  restart      Restart FrankenPHP server"
    echo "  install      Install FrankenPHP binary"
    echo "  docker-up    Start with Docker Compose"
    echo "  docker-down  Stop Docker Compose"
    echo "  docker-logs  Show Docker logs"
    echo "  status       Check FrankenPHP status"
    echo "  help         Show this help message"
}

# Check if FrankenPHP is installed
check_frankenphp() {
    if ! command -v frankenphp &> /dev/null; then
        print_color "RED" "FrankenPHP is not installed. Please run './frankenphp.sh install' first."
        return 1
    fi
    return 0
}

# Install FrankenPHP
install_frankenphp() {
    print_color "BLUE" "Installing FrankenPHP..."

    # Detect OS
    case "$(uname -s)" in
        Linux*)
            print_color "YELLOW" "Downloading FrankenPHP for Linux..."
            curl -fsSL https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o frankenphp
            ;;
        Darwin*)
            print_color "YELLOW" "Downloading FrankenPHP for macOS..."
            curl -fsSL https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-mac-x86_64 -o frankenphp
            ;;
        CYGWIN*|MINGW32*|MSYS*|MINGW*)
            print_color "YELLOW" "Downloading FrankenPHP for Windows..."
            curl -fsSL https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-windows-x86_64.exe -o frankenphp.exe
            ;;
        *)
            print_color "RED" "Unsupported operating system: $(uname -s)"
            exit 1
            ;;
    esac

    # Make executable
    chmod +x frankenphp*

    # Move to PATH (optional)
    if [[ ":$PATH:" == *":$PWD:"* ]]; then
        print_color "GREEN" "FrankenPHP installed successfully in current directory!"
    else
        print_color "YELLOW" "FrankenPHP installed in current directory. Add $PWD to your PATH or move frankenphp to /usr/local/bin"
    fi
}

# Start FrankenPHP
start_frankenphp() {
    if ! check_frankenphp; then
        return 1
    fi

    print_color "BLUE" "Starting FrankenPHP server..."

    # Ensure Laravel is ready
    if [ ! -f ".env" ]; then
        print_color "YELLOW" "Creating .env file from .env.example..."
        cp .env.example .env
    fi

    # Install dependencies if needed
    if [ ! -d "vendor" ]; then
        print_color "YELLOW" "Installing Composer dependencies..."
        composer install
    fi

    # Generate app key if needed
    if ! grep -q "APP_KEY=" .env || [ -z "$(grep "APP_KEY=" .env | cut -d'=' -f2)" ]; then
        print_color "YELLOW" "Generating application key..."
        php artisan key:generate
    fi

    # Set Octane server to FrankenPHP
    if ! grep -q "OCTANE_SERVER=frankenphp" .env; then
        echo "OCTANE_SERVER=frankenphp" >> .env
        print_color "YELLOW" "Added OCTANE_SERVER=frankenphp to .env"
    fi

    # Start FrankenPHP
    print_color "GREEN" "Starting FrankenPHP on http://localhost:8000"
    frankenphp run --config Caddyfile
}

# Stop FrankenPHP
stop_frankenphp() {
    print_color "BLUE" "Stopping FrankenPHP server..."
    pkill -f frankenphp || print_color "YELLOW" "FrankenPHP was not running"
    print_color "GREEN" "FrankenPHP stopped"
}

# Start with Docker
docker_up() {
    print_color "BLUE" "Starting Laravel ERP with Docker Compose and FrankenPHP..."

    # Build and start containers
    docker-compose up -d --build

    print_color "GREEN" "Services started successfully!"
    print_color "BLUE" "Application: http://localhost:8000"
    print_color "BLUE" "phpMyAdmin: http://localhost:8080"
    print_color "YELLOW" "Use 'docker-compose logs -f app' to see application logs"
}

# Stop Docker
docker_down() {
    print_color "BLUE" "Stopping Docker Compose services..."
    docker-compose down
    print_color "GREEN" "Services stopped"
}

# Show Docker logs
docker_logs() {
    print_color "BLUE" "Showing Docker logs for Laravel application..."
    docker-compose logs -f app
}

# Check status
check_status() {
    print_color "BLUE" "Checking FrankenPHP status..."

    if pgrep -f frankenphp > /dev/null; then
        print_color "GREEN" "FrankenPHP is running"
        print_color "BLUE" "PID: $(pgrep -f frankenphp)"
    else
        print_color "RED" "FrankenPHP is not running"
    fi

    # Check if port 8000 is in use
    if command -v lsof &> /dev/null; then
        if lsof -i :8000 &> /dev/null; then
            print_color "GREEN" "Port 8000 is in use"
        else
            print_color "YELLOW" "Port 8000 is free"
        fi
    fi
}

# Main script logic
case "${1:-help}" in
    start)
        start_frankenphp
        ;;
    stop)
        stop_frankenphp
        ;;
    restart)
        stop_frankenphp
        sleep 2
        start_frankenphp
        ;;
    install)
        install_frankenphp
        ;;
    docker-up)
        docker_up
        ;;
    docker-down)
        docker_down
        ;;
    docker-logs)
        docker_logs
        ;;
    status)
        check_status
        ;;
    help|*)
        print_usage
        ;;
esac
