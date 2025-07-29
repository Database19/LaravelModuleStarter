# 🚨 FRANKENPHP WINDOWS EMERGENCY GUIDE

## CRITICAL BUG ALERT
FrankenPHP Octane method dapat menyebabkan laptop hang dan perlu restart!

## ✅ SAFE METHODS (Gunakan Ini)

### 1. Docker Method (RECOMMENDED)
```bash
frankenphp.bat docker
```

### 2. FrankenPHP Binary
```bash
frankenphp.bat binary
```

### 3. Laravel Default Server
```bash
php artisan serve
```

## 🚫 DANGEROUS METHODS (JANGAN GUNAKAN!)

- ❌ `frankenphp.bat octane`
- ❌ `frankenphp.bat watch` 
- ❌ `php artisan frankenphp:start`
- ❌ `php artisan octane:start --server=frankenphp`

## 🆘 EMERGENCY PROCEDURES

### If System Hangs:
1. Run: `emergency-stop.bat`
2. If still hanging: Open Task Manager (Ctrl+Shift+Esc)
3. Kill all `php.exe` and `frankenphp.exe` processes
4. Restart if necessary

### Prevention:
- Always use Docker method for FrankenPHP
- Use Laravel default server for development
- Avoid any Octane/FrankenPHP combinations on Windows

## 📞 Quick Commands Reference

```bash
# Start safely
frankenphp.bat docker

# Stop everything  
frankenphp.bat stop

# Emergency stop
emergency-stop.bat

# Show safe options
frankenphp.bat help

# Laravel default (always safe)
php artisan serve
```

## 📖 More Information
- Read: `WINDOWS-TROUBLESHOOTING.md`
- Read: `FRANKENPHP.md`
- Read: `README.md`

---
**Remember**: Docker method is the safest and most reliable for Windows users!
