# Environment Configuration Guide

## Local Development (.env.local)

Create a `.env.local` file for local development:

```env
# Local Development Environment
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:TsGrt0fPalGDPJZZvabSmPnnWQScHn6jvxCfgKmyNHI=
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tyrese_db
DB_USERNAME=tyrese_user
DB_PASSWORD=tyrese_password_2024

# Session Configuration for Local Development
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

# CORS Configuration for Local Development
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,http://localhost:8000

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tyresemuigai09@gmail.com
MAIL_PASSWORD=rqodqlrxauatbeos
MAIL_FROM_ADDRESS="tyresemuigai09@gmail.com"
MAIL_FROM_NAME="SokoFresh"
```

## Staging Environment (.env.staging)

Create a `.env.staging` file for staging:

```env
# Staging Environment
APP_NAME=Laravel
APP_ENV=staging
APP_KEY=base64:TsGrt0fPalGDPJZZvabSmPnnWQScHn6jvxCfgKmyNHI=
APP_DEBUG=false
APP_URL=https://rbuinventory-staging.sokolink.co.ke
FRONTEND_URL=https://rbuinventory-staging.sokolink.co.ke

# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tyrese_db
DB_USERNAME=tyrese_user
DB_PASSWORD=tyrese_password_2024

# Session Configuration for Staging
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=rbuinventory-staging.sokolink.co.ke
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# CORS Configuration for Staging
CORS_ALLOWED_ORIGINS=https://rbuinventory-staging.sokolink.co.ke,https://*.sokolink.co.ke

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tyresemuigai09@gmail.com
MAIL_PASSWORD=rqodqlrxauatbeos
MAIL_FROM_ADDRESS="tyresemuigai09@gmail.com"
MAIL_FROM_NAME="SokoFresh"
```

## How to Switch Environments

### For Local Development:
```bash
cp .env.local .env
php artisan config:clear
php artisan cache:clear
```

### For Staging:
```bash
cp .env.staging .env
php artisan config:clear
php artisan cache:clear
```

## Key Differences:

### Local Development:
- `APP_URL=http://localhost:8000`
- `SESSION_DOMAIN=localhost`
- `SESSION_SECURE_COOKIE=false`
- `CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,http://localhost:8000`

### Staging:
- `APP_URL=https://rbuinventory-staging.sokolink.co.ke`
- `SESSION_DOMAIN=rbuinventory-staging.sokolink.co.ke`
- `SESSION_SECURE_COOKIE=true`
- `CORS_ALLOWED_ORIGINS=https://rbuinventory-staging.sokolink.co.ke,https://*.sokolink.co.ke`

## Benefits:
1. **Environment-specific configuration**
2. **Easy switching between local and staging**
3. **No more conflicts between environments**
4. **Proper CORS settings for each environment**
