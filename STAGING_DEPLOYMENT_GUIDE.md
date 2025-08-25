# Staging Deployment Guide - Sokofresh

## CSRF Token Issues (419 Error) - Quick Fix

### Problem:
After successful login, the admin interface shows a 419 error (CSRF token mismatch) and redirects back to login.

### Root Cause:
- CORS configuration doesn't allow staging domain
- Session configuration may not be optimized for staging
- CSRF tokens expire or become invalid

### Solution Steps:

#### 1. Update Environment Variables
Add these to your staging `.env` file:

```env
# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=your-staging-domain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# App Configuration
APP_URL=https://your-staging-domain.com
FRONTEND_URL=https://your-staging-domain.com
APP_ENV=staging

# CORS Configuration
CORS_ALLOWED_ORIGINS=https://your-staging-domain.com
```

#### 2. Update CORS Configuration
Replace `your-staging-domain.com` in `config/cors.php` with your actual staging domain:

```php
'allowed_origins' => [
    'http://localhost:5173', 
    'http://localhost:8000',
    'https://localhost:5173',
    'https://localhost:8000',
    'https://your-actual-staging-domain.com',  // Replace this
    'https://*.your-actual-staging-domain.com', // Replace this
],
```

#### 3. Clear Application Cache
Run these commands on your staging server:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:table  # If using database sessions
php artisan migrate        # To create sessions table if needed
```

#### 4. Test CSRF Token Endpoint
Test the new CSRF token endpoint:

```bash
curl https://your-staging-domain.com/api/csrf-token
```

Should return:
```json
{
    "token": "your-csrf-token",
    "timestamp": "2025-08-15 10:30:00"
}
```

#### 5. Check Session Table
Ensure the sessions table exists and is working:

```bash
php artisan tinker
>>> DB::table('sessions')->count();
```

### Alternative Solutions:

#### Option A: Use File Sessions (Simpler)
If database sessions are causing issues, switch to file sessions:

```env
SESSION_DRIVER=file
```

#### Option B: Disable CSRF for API Routes
If the issue persists, you can disable CSRF for API routes by updating the middleware:

```php
// In app/Http/Kernel.php, remove VerifyCsrfToken from api middleware group
```

#### Option C: Extend Session Lifetime
Increase session lifetime for staging:

```env
SESSION_LIFETIME=480  # 8 hours instead of 2 hours
```

### Testing Steps:

1. **Clear browser cache and cookies**
2. **Test login flow**
3. **Check if 419 error persists**
4. **Monitor logs for CSRF errors**

### Log Monitoring:

Check these log entries:
```bash
tail -f storage/logs/laravel.log | grep -i csrf
tail -f storage/logs/laravel.log | grep -i session
```

### Emergency Fix:

If the issue persists, temporarily disable CSRF verification:

```php
// In app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    '*',  // Disable CSRF for all routes (temporary fix)
];
```

**⚠️ Warning: Only use this as a temporary fix for testing. Re-enable CSRF protection for production.**

## Contact Information
If you need additional help, provide:
1. Staging domain URL
2. Error logs
3. Browser console errors
4. Network tab information
