# Windows Troubleshooting untuk FrankenPHP

## 🚨 EMERGENCY NOTICE

**CRITICAL BUG DISCOVERED**: Octane method dapat menyebabkan sistem hang dan memerlukan restart laptop!

### Immediate Actions Required:

1. **JANGAN gunakan**: `frankenphp.bat octane` atau `frankenphp.bat watch`
2. **JANGAN jalankan**: `php artisan frankenphp:start`
3. **JANGAN jalankan**: `php artisan octane:start --server=frankenphp`

### Emergency Stop:
Jika sistem sudah hang karena menjalankan command di atas:
```batch
# Jalankan emergency stop script
emergency-stop.bat
```

## ✅ SAFE METHODS (Rekomendasi)

### Method 1: Docker (RECOMMENDED)
```batch
frankenphp.bat docker
```

### Method 2: FrankenPHP Binary
```batch
frankenphp.bat binary
```

### Method 3: Laravel Default Server
```batch
php artisan serve
```

## � DISABLED METHODS (Berbahaya di Windows)

- ❌ `frankenphp.bat octane` - Dapat menyebabkan hang
- ❌ `frankenphp.bat watch` - Dapat menyebabkan hang  
- ❌ `php artisan frankenphp:start` - Dapat menyebabkan hang
- ❌ `php artisan octane:start --server=frankenphp` - Dapat menyebabkan hang

### Issue 1: "Undefined constant SIGINT" Error

**Problem:**
```
Error: Undefined constant "Laravel\Octane\Commands\Concerns\SIGINT"
```

**Root Cause:**
Windows tidak mendefinisikan konstanta signal Unix/Linux secara default.

**Solutions (dalam urutan prioritas):**

#### Solution 1: Gunakan FrankenPHP Binary (RECOMMENDED)
```cmd
# Install binary
frankenphp.bat install

# Start dengan binary
frankenphp.bat start-binary
```

#### Solution 2: Gunakan Docker (RECOMMENDED untuk Development)
```cmd
# Start dengan Docker
frankenphp.bat start-docker

# Atau manual
docker-compose up -d
```

#### Solution 3: Laravel Octane dengan Windows Compatibility
```cmd
# Gunakan wrapper yang sudah diperbaiki
frankenphp.bat start-octane
```

#### Solution 4: Manual Octane dengan Flag
```cmd
# Dengan --no-reload flag untuk menghindari signal issues
php artisan octane:start --server=frankenphp --port=8000 --no-reload
```

#### Solution 5: Fallback ke RoadRunner
```cmd
# Ubah di .env
OCTANE_SERVER=roadrunner

# Start dengan RoadRunner
php artisan octane:start --server=roadrunner --port=8000
```

### Issue 2: Port 8000 Already in Use

**Check apa yang menggunakan port:**
```cmd
netstat -ano | findstr :8000
```

**Kill process:**
```cmd
taskkill /PID <PID> /F
```

### Issue 3: FrankenPHP Binary Download Failed

**Manual Download:**
1. Go to: https://github.com/dunglas/frankenphp/releases/latest
2. Download: `frankenphp-windows-x86_64.exe`
3. Rename to: `frankenphp.exe`
4. Place in project root

### Issue 4: Docker Issues

**Check Docker is running:**
```cmd
docker --version
docker-compose --version
```

**Common Docker fixes:**
```cmd
# Reset Docker
docker system prune -a

# Rebuild containers
docker-compose down
docker-compose up -d --build
```

## 🔧 Recommended Setup untuk Windows

### Option 1: Docker (Easiest)
```cmd
# One command untuk start semua
docker-compose up -d

# Access aplikasi
# http://localhost:8000 - Laravel App
# http://localhost:8080 - phpMyAdmin
```

### Option 2: FrankenPHP Binary
```cmd
# Install binary
frankenphp.bat install

# Start server
frankenphp.bat start-binary
```

### Option 3: WSL2 (Best Performance)
1. Install WSL2
2. Install Docker Desktop dengan WSL2 backend
3. Clone project di WSL2
4. Run dari dalam WSL2

## 🚀 Quick Commands

### Status Check
```cmd
frankenphp.bat status
```

### Stop All
```cmd
frankenphp.bat stop
```

### Restart
```cmd
frankenphp.bat restart
```

### View Logs
```cmd
# Docker logs
frankenphp.bat docker-logs

# Laravel logs
tail -f storage/logs/laravel.log
```

## ⚙️ Environment Configuration

### .env Settings untuk Windows
```env
# Server Configuration
OCTANE_SERVER=frankenphp
OCTANE_HTTPS=false
APP_URL=http://localhost:8000

# Database (untuk Docker)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_erp
DB_USERNAME=laravel
DB_PASSWORD=password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PORT=6379
```

## 🛠️ Development Tips

### Hot Reloading
- **Docker**: Supports hot reload by default
- **Binary**: Manual restart needed
- **Octane**: Use `--watch` flag (may have issues on Windows)

### Performance
- **Docker**: Good for development, consistent environment
- **Binary**: Best performance, closest to production
- **Octane**: Good performance, but signal issues on Windows

### Debugging
```cmd
# Debug mode
php artisan octane:start --server=frankenphp --watch --debug

# Check configuration
php artisan octane:status
```

## 📊 Performance Comparison

| Method | Performance | Stability | Ease of Use | Hot Reload |
|--------|------------|-----------|-------------|------------|
| Docker | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| FrankenPHP Binary | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐ |
| Laravel Octane | ⭐⭐⭐⭐ | ⭐⭐ | ⭐⭐ | ⭐ |

## 🆘 When All Else Fails

1. **Use Docker** - Most reliable for Windows
2. **Use WSL2** - Best of both worlds
3. **Use php artisan serve** - Basic Laravel server
4. **Use XAMPP/WAMP** - Traditional approach

## 📞 Support

Jika masih mengalami masalah:
1. Check issue ini di GitHub Laravel Octane
2. Pertimbangkan menggunakan WSL2
3. Gunakan Docker sebagai fallback
4. Contact development team dengan log error yang lengkap
