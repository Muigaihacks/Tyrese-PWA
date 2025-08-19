# Sokofresh Deployment Troubleshooting Guide

## Quick Health Checks

### 1. Database Connection Test
```bash
# Test the health endpoint
curl http://your-domain.com/api/health

# Expected response:
{
  "status": "healthy",
  "timestamp": "2025-08-15 09:48:39",
  "app_name": "Laravel",
  "app_env": "production",
  "app_url": "https://your-domain.com",
  "database": {
    "connection": "pgsql",
    "host": "127.0.0.1",
    "port": "5432",
    "database": "tyrese_db",
    "status": "connected"
  }
}
```

### 2. Detailed System Status
```bash
# Get detailed system information
curl http://your-domain.com/api/status

# This will show:
# - PHP version
# - Laravel version
# - Database configuration
# - Cache configuration
# - Mail configuration
# - Database connectivity status
```

### 3. Check Application Logs
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Look for these key log entries:
# - "Database connection successful"
# - "Sokofresh application started"
# - "API Request received"
# - "Health check passed"
# - Any error messages
```

## Common Issues and Solutions

### Database Connection Issues
**Symptoms:**
- Health check returns `"status": "unhealthy"`
- Database status shows `"failed"`
- Error messages in logs

**Check:**
1. Database server is running
2. Database credentials in `.env` file
3. Network connectivity to database
4. Database exists and is accessible

**Log entries to look for:**
```
[ERROR] Database connection failed
[ERROR] Application exception occurred
[CRITICAL] Database query exception
```

### HTTPS Issues
**Symptoms:**
- App works on HTTP but not HTTPS
- Mixed content errors
- SSL certificate issues

**Check:**
1. SSL certificate is valid
2. Force HTTPS in `.env`: `APP_URL=https://your-domain.com`
3. Check for mixed content (HTTP/HTTPS)
4. Verify CORS settings for HTTPS

### API Endpoint Issues
**Symptoms:**
- 404 errors on API endpoints
- Authentication failures
- CORS errors

**Check:**
1. Routes are properly configured
2. Middleware is working
3. CORS headers are set correctly
4. Authentication tokens are valid

## Log Analysis

### Key Log Entries to Monitor

**Successful Startup:**
```
[INFO] Sokofresh application started
[INFO] Database connection successful
```

**API Requests:**
```
[INFO] API Request received
[INFO] API Response sent
```

**Health Checks:**
```
[INFO] Health check passed
[INFO] System status check
```

**Errors:**
```
[ERROR] Database connection failed
[ERROR] Application exception occurred
[CRITICAL] Database query exception
```

## Environment Configuration

### Required Environment Variables
```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:your-key-here
APP_URL=https://your-domain.com
FRONTEND_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=tyrese_db
DB_USERNAME=tyrese_user
DB_PASSWORD=tyrese_password_2024

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tyresemuigai09@gmail.com
MAIL_PASSWORD=your-app-password
```

## Testing Commands

### Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO object or throw exception
```

### Test Mail Configuration
```bash
php artisan tinker
>>> Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test'); });
```

### Clear Application Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Contact Information
If you need additional help, check the logs first and provide:
1. Health check response
2. Relevant log entries
3. Error messages
4. Environment details (without sensitive data)
