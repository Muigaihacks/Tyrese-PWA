# Admin Redirect Loop Fix - Staging

## Problem: "Too many redirects" error on admin login

### Quick Fix Steps:

#### 1. Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:table  # Create sessions table if needed
php artisan migrate        # Run migrations
```

#### 2. Check Session Configuration
Ensure your staging `.env` has:
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=rbuinventory-staging.sokolink.co.ke
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

#### 3. Test Authentication Status
Visit this URL to check if authentication is working:
```
https://rbuinventory-staging.sokolink.co.ke/debug-auth
```

Should return JSON with authentication status.

#### 4. Check Logs for Redirect Loop
```bash
tail -f storage/logs/laravel.log | grep -i "admin middleware"
```

Look for patterns like:
- "User not authenticated, redirecting to login" (repeated)
- "Admin middleware check" (repeated)

#### 5. Clear Browser Data
- Clear all cookies for the staging domain
- Clear browser cache
- Try incognito/private browsing mode

#### 6. Test Admin User Creation
Create a new admin user:
```bash
php artisan create:admin admin@example.com password123
```

#### 7. Check Database Sessions
```bash
php artisan tinker
>>> DB::table('sessions')->count();
>>> DB::table('sessions')->latest()->first();
```

### Common Issues & Solutions:

#### Issue 1: Session Table Missing
```bash
php artisan session:table
php artisan migrate
```

#### Issue 2: Role Assignment Failed
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'admin@example.com')->first();
>>> $user->assignRole('admin');
```

#### Issue 3: CORS Issues
Check if the staging domain is in `config/cors.php`:
```php
'allowed_origins' => [
    'https://rbuinventory-staging.sokolink.co.ke',
    // ... other domains
],
```

#### Issue 4: CSRF Token Issues
Test CSRF token endpoint:
```bash
curl https://rbuinventory-staging.sokolink.co.ke/api/csrf-token
```

### Emergency Fix (Temporary):
If the issue persists, temporarily disable admin middleware:

```php
// In app/Http/Kernel.php, comment out the admin middleware
// 'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
```

**⚠️ Warning: Only use this for testing. Re-enable for production.**

### Debug Information to Collect:
1. Browser console errors
2. Network tab requests
3. Laravel logs
4. Authentication status from `/debug-auth`
5. Session table contents

### Contact Information:
If you need help, provide:
1. Laravel logs
2. Browser console errors
3. Authentication status from debug endpoint
4. Session table contents
