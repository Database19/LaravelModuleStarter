# FrankenPHP Setup untuk Laravel ERP

FrankenPHP adalah server web modern yang menggabungkan PHP dan Caddy web server. Ini memberikan performa yang sangat baik untuk aplikasi Laravel dengan fitur seperti HTTP/2, HTTP/3, dan worker mode.

## 🚀 Cara Menjalankan FrankenPHP

### Metode 1: Command Windows-Compatible (RECOMMENDED untuk Windows)
```bash
# Start server dengan Windows compatibility
php artisan frankenphp:start --port=8000

# Start dengan file watching (development)
php artisan frankenphp:start --port=8000 --watch

# Start dengan worker specific
php artisan frankenphp:start --port=8000 --workers=4
```

### Metode 2: Menggunakan Helper Script

#### Windows (PowerShell)
```powershell
# Install FrankenPHP
.\frankenphp.ps1 install

# Start server
.\frankenphp.ps1 start

# Check status
.\frankenphp.ps1 status

# Stop server
.\frankenphp.ps1 stop
```

#### Linux/macOS (Bash)
```bash
# Make script executable
chmod +x frankenphp.sh

# Install FrankenPHP
./frankenphp.sh install

# Start server
./frankenphp.sh start

# Check status
./frankenphp.sh status

# Stop server
./frankenphp.sh stop
```

### Option 2: Menggunakan Docker (Recommended untuk Development)

```bash
# Start semua services (Laravel + MySQL + Redis + phpMyAdmin)
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down
```

Setelah docker berjalan:
- **Aplikasi Laravel**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **MySQL**: localhost:3306 (username: laravel, password: password)
- **Redis**: localhost:6379

### Option 3: Manual Installation

#### 1. Install FrankenPHP Binary

**Windows:**
```cmd
curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-windows-x86_64.exe -o frankenphp.exe
```

**Linux:**
```bash
curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o frankenphp
chmod +x frankenphp
```

**macOS:**
```bash
curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-mac-x86_64 -o frankenphp
chmod +x frankenphp
```

#### 2. Setup Laravel

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Set Octane server to FrankenPHP
echo "OCTANE_SERVER=frankenphp" >> .env

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

#### 3. Start FrankenPHP

```bash
# Windows
.\frankenphp.exe run --config Caddyfile

# Linux/macOS
./frankenphp run --config Caddyfile
```

## 📁 File Konfigurasi

### Caddyfile
File konfigurasi dasar untuk development dengan pengaturan sederhana.

### Caddyfile.advanced
File konfigurasi lanjutan dengan:
- Security headers
- Compression
- Caching
- Rate limiting
- Production optimizations

### docker-compose.yml
Setup lengkap dengan:
- FrankenPHP (aplikasi Laravel)
- MySQL 8.0
- Redis 7
- phpMyAdmin

### Dockerfile
Build custom image dengan semua ekstensi PHP yang diperlukan.

## ⚙️ Konfigurasi

### Environment Variables

Tambahkan ke file `.env`:

```env
# FrankenPHP Configuration
OCTANE_SERVER=frankenphp
OCTANE_HTTPS=false

# Performance Settings
OCTANE_WORKERS=8
OCTANE_MAX_REQUESTS=1000

# Database (untuk Docker)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_erp
DB_USERNAME=laravel
DB_PASSWORD=password

# Cache (untuk Docker)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Worker Configuration

Edit `config/octane.php` untuk menyesuaikan:

```php
'server' => env('OCTANE_SERVER', 'frankenphp'),

'frankenphp' => [
    'workers' => env('OCTANE_WORKERS', 8),
    'max_requests' => env('OCTANE_MAX_REQUESTS', 1000),
],
```

## 🔧 Commands

### Script Helper Commands

```bash
# Start/Stop
start        # Start FrankenPHP server
stop         # Stop FrankenPHP server
restart      # Restart FrankenPHP server

# Docker
docker-up    # Start with Docker Compose
docker-down  # Stop Docker Compose
docker-logs  # Show Docker logs

# Utilities
install      # Install FrankenPHP binary
status       # Check FrankenPHP status
help         # Show help message
```

### Artisan Commands

```bash
# Start with Octane
php artisan octane:start --server=frankenphp --port=8000

# Start with watch mode (auto-restart on file changes)
php artisan octane:start --server=frankenphp --watch

# Stop Octane
php artisan octane:stop

# Reload workers
php artisan octane:reload

# Check status
php artisan octane:status
```

## 🚀 Performance Tips

### 1. Worker Optimization
- Set workers berdasarkan CPU cores: `workers = cores × 2`
- Monitor memory usage dan adjust `max_requests`

### 2. Caching
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all caches
php artisan optimize:clear
```

### 3. Database Optimization
- Gunakan connection pooling
- Enable query caching
- Optimize database queries

### 4. Static Assets
- Gunakan CDN untuk assets
- Enable gzip compression
- Set proper cache headers

## 🐛 Troubleshooting

### Common Issues

**1. Port 8000 sudah digunakan**
```bash
# Check yang menggunakan port
# Windows
netstat -ano | findstr :8000

# Linux/macOS
lsof -i :8000

# Kill process
# Windows
taskkill /PID <PID> /F

# Linux/macOS
kill -9 <PID>
```

**2. FrankenPHP tidak start**
- Check file permissions
- Verify .env configuration
- Check error logs

**3. Database connection error**
- Verify database credentials
- Check if MySQL service is running
- Test connection manually

**4. Memory issues**
- Reduce number of workers
- Increase `max_requests` limit
- Monitor memory usage

### Debug Mode

```bash
# Enable debug mode
php artisan octane:start --server=frankenphp --watch --debug

# Check logs
tail -f storage/logs/laravel.log

# Docker logs
docker-compose logs -f app
```

## 📊 Monitoring

### Health Check
```bash
# Check application health
curl http://localhost:8000/health

# Check FrankenPHP admin (if enabled)
curl http://localhost:2019/metrics
```

### Performance Monitoring
- Monitor response times
- Check memory usage
- Monitor database queries
- Track error rates

## 🔒 Security

### Production Settings
- Disable debug mode
- Enable HTTPS
- Set security headers
- Configure firewall
- Regular security updates

### Environment Security
```env
APP_ENV=production
APP_DEBUG=false
OCTANE_HTTPS=true
```

## 🆙 Updates

### Update FrankenPHP
```bash
# Using script
./frankenphp.sh install

# Manual download
curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o frankenphp
```

### Update Laravel
```bash
composer update
php artisan migrate
php artisan octane:reload
```

## 📚 Resources

- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Laravel Octane Documentation](https://laravel.com/docs/octane)
- [Caddy Web Server Documentation](https://caddyserver.com/docs/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)

## 🆘 Support

Jika mengalami masalah:
1. Check troubleshooting section di atas
2. Review log files
3. Check GitHub issues
4. Contact development team
