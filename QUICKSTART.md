# 🚀 Quick Start - FrankenPHP pada Windows

## Cara Tercepat (Docker - RECOMMENDED)

```cmd
# 1. Start semua services dalam satu command
docker-compose up -d

# 2. Tunggu beberapa detik, kemudian buka browser
http://localhost:8000
```

**Akses Services:**
- 🌐 **Laravel App**: http://localhost:8000
- 🗄️ **phpMyAdmin**: http://localhost:8080
- 🔴 **MySQL**: localhost:3306 (username: laravel, password: password)
- 🔵 **Redis**: localhost:6379

## Alternatif 1: FrankenPHP Binary

```cmd
# Install dan jalankan dalam satu command
frankenphp.bat start
```

## Alternatif 2: Laravel Octane

```cmd
# Jika binary tidak tersedia
frankenphp.bat start-octane
```

## 🛑 Stop Server

```cmd
# Stop binary/octane
frankenphp.bat stop

# Stop Docker
docker-compose down
```

## 🔧 Commands Berguna

```cmd
# Check status
frankenphp.bat status

# View Docker logs
docker-compose logs -f app

# Restart
frankenphp.bat restart
```

## ❌ Troubleshooting

Jika mengalami error, baca: **WINDOWS-TROUBLESHOOTING.md**

**Quick fixes:**
1. Pastikan port 8000 tidak digunakan
2. Gunakan Docker jika binary/octane bermasalah
3. Check apakah Docker Desktop berjalan

## 🎯 Development Tips

- **Docker**: Paling stabil, auto-reload
- **Binary**: Performa terbaik
- **Octane**: Bisa ada masalah signal di Windows

**Recommended untuk pengembangan: Docker**
