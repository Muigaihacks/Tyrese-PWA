# Deploying SokoFresh (Tyrese-PWA) to Railway

This guide will help you deploy the SokoFresh system to Railway.app. Railway is easier to set up than Render and offers a better free tier experience.

## Prerequisites

1. Code pushed to GitHub repository
2. Railway.app account (sign up at [railway.app](https://railway.app))
   - Free tier includes $5 credit/month
   - Perfect for small projects like this

## Step-by-Step Deployment

### 1. Create Railway Project

1. Go to [Railway Dashboard](https://railway.app/dashboard)
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Authorize Railway to access your GitHub (if first time)
5. Select your **Tyrese-PWA** repository
6. Railway will automatically detect it's a PHP/Laravel project

### 2. Add PostgreSQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"Add PostgreSQL"**
3. Railway will automatically:
   - Create the database
   - Add database environment variables to your service
   - Link the database to your app

### 3. Configure Environment Variables

Click on your web service, then go to **"Variables"** tab and add:

#### Application Variables
- `APP_NAME` = `SokoFresh`
- `APP_ENV` = `production`
- `APP_DEBUG` = `false`
- `APP_URL` = (Railway will provide this after first deploy - use `https://your-app-name.up.railway.app`)
- `APP_KEY` = (generate with: `php artisan key:generate --show` locally, then paste here)

#### Admin User Variables (Auto-created on deployment)
- `ADMIN_NAME` = `Admin` (optional, defaults to "Admin")
- `ADMIN_EMAIL` = `admin@demo.com` (optional, defaults to "admin@demo.com")
- `ADMIN_PASSWORD` = `demo123` (optional, but **CHANGE THIS!**)
- `ADMIN_ROLE` = `admin` (optional, defaults to "admin")

#### Frontend/API Variables
- `FRONTEND_URL` = (same as APP_URL - set after first deploy)
- `CORS_ALLOWED_ORIGINS` = (same as APP_URL - set after first deploy)
- `SANCTUM_STATEFUL_DOMAINS` = `your-app-name.up.railway.app` (without https://)

#### Other Variables
- `LOG_CHANNEL` = `stderr` (for Railway to capture logs)

### 4. Database Variables (Auto-configured)

Railway automatically adds these when you link the PostgreSQL database:
- `DATABASE_URL` (Railway handles this automatically)
- `PGHOST`
- `PGPORT`
- `PGDATABASE`
- `PGUSER`
- `PGPASSWORD`

**However**, you may need to set these manually for Laravel:
- `DB_CONNECTION` = `pgsql`
- `DB_HOST` = (use Railway's template variable: `${{Postgres.PGHOST}}`)
- `DB_PORT` = `5432` (or use `${{Postgres.PGPORT}}`)
- `DB_DATABASE` = (use `${{Postgres.PGDATABASE}}`)
- `DB_USERNAME` = (use `${{Postgres.PGUSER}}`)
- `DB_PASSWORD` = (use `${{Postgres.PGPASSWORD}}`)

**Easy way:** Railway provides template variables. Click "Reference Variable" in the Variables tab and select from your PostgreSQL service. This is the easiest method!

### 5. Generate APP_KEY

Run this locally to generate your APP_KEY:
```bash
php artisan key:generate --show
```

Copy the output and add it as `APP_KEY` in Railway's environment variables.

### 6. First Deploy

1. Railway will automatically start building and deploying
2. Wait for the build to complete (usually 2-4 minutes)
3. Once deployed, Railway will provide a public URL like: `https://your-app-name.up.railway.app`

### 7. Update URLs After First Deploy

After the first deploy, update these environment variables with your actual Railway URL:

1. Go to your service → **"Variables"**
2. Update:
   - `APP_URL` = `https://your-actual-url.up.railway.app`
   - `FRONTEND_URL` = `https://your-actual-url.up.railway.app`
   - `CORS_ALLOWED_ORIGINS` = `https://your-actual-url.up.railway.app`
   - `SANCTUM_STATEFUL_DOMAINS` = `your-actual-url.up.railway.app` (no https://)
3. Railway will automatically redeploy with the new variables

### 8. Access Your Application

- **Admin Panel:** `https://your-app-name.up.railway.app/admin`
- **User Interface:** `https://your-app-name.up.railway.app`
- **API Endpoints:** `https://your-app-name.up.railway.app/api/*`

### 9. Admin User (Automatic!)

The admin user is created automatically during deployment using the `admin:create-from-env` command. 

**Default credentials** (if you didn't set environment variables):
- Email: `admin@demo.com`
- Password: `demo123`

**To customize**, set `ADMIN_EMAIL` and `ADMIN_PASSWORD` environment variables before deploying.

## Important Notes

### URL Configuration (Critical!)

**IMPORTANT:** Make sure `APP_URL` matches your Railway service URL exactly:
- After first deploy, Railway provides: `https://your-app-name.up.railway.app`
- Set `APP_URL=https://your-app-name.up.railway.app`
- Set `FRONTEND_URL=https://your-app-name.up.railway.app`
- Set `CORS_ALLOWED_ORIGINS=https://your-app-name.up.railway.app`
- Set `SANCTUM_STATEFUL_DOMAINS=your-app-name.up.railway.app` (no https://)

**Do NOT use localhost URLs in production!**

### Free Tier Limitations

- **$5 credit/month** - Usually enough for small projects
- **Auto-scales** - No sleeping like Render's free tier
- **Custom domains** - Supported
- **May require credit card** - But won't charge unless you exceed free tier

### Railway vs Render

| Feature | Railway | Render |
|---------|---------|--------|
| Free Tier | $5 credit/month | Free (sleeps after 15 min) |
| Setup Time | ~5-10 min | ~15-20 min |
| Auto-sleep | ❌ No | ✅ Yes (free tier) |
| Custom Domain | ✅ Yes | ✅ Yes |
| Ease of Use | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

**Railway is better for:**
- Active projects that need to stay awake
- Faster deployment
- Better developer experience

## Troubleshooting

### Build Fails

**Error: "composer install failed" - Missing PHP extensions (intl, zip)**
- This error occurs when Railway's Railpack doesn't install required PHP extensions
- **Solution:** The project now includes a `Dockerfile` that explicitly installs all required PHP extensions (intl, zip, and others)
- Railway will automatically use the Dockerfile instead of Railpack when it's present
- The Dockerfile installs: `pdo_mysql`, `pdo_pgsql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`, `intl`, `zip`
- No additional configuration needed - just commit and push the Dockerfile

**Error: "composer install failed" - Other issues**
- Check PHP version compatibility (requires PHP 8.2+)
- Verify `composer.json` is valid
- Check Railway logs for specific errors

**Error: "npm install failed"**
- Check Node.js version compatibility
- Verify `package.json` is valid

### App Shows 500 Error

**Check:**
1. Logs in Railway dashboard (click on your service → "Deployments" → "View Logs")
2. Verify `APP_KEY` is set
3. **Verify `APP_URL` is set to your Railway URL (not localhost!)**
4. Check database connection variables

**Fix:**
- Update environment variables and redeploy
- Railway will automatically redeploy when you change variables

### Database Connection Errors

**Error: "SQLSTATE[HY000] [2002]"**
- Verify database is linked to your service
- Check `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` are set correctly
- **Use Railway's template variables:** `${{Postgres.PGHOST}}` etc. (click "Reference Variable" in Variables tab)

### API Calls Fail or CORS Errors

**Fix:**
- Verify `CORS_ALLOWED_ORIGINS` includes your Railway URL
- Verify `SANCTUM_STATEFUL_DOMAINS` includes your Railway domain (without https://)
- Check that `FRONTEND_URL` matches `APP_URL`

### Admin User Not Created

**Fix:**
1. Check deployment logs for `admin:create-from-env` command output
2. Verify environment variables are set
3. The command skips creation if user already exists (check email)

## Production Optimizations

After successful deployment:

1. **Set up custom domain** (optional):
   - Go to service → "Settings" → "Domains"
   - Add your custom domain
   - Update `APP_URL` and related variables

2. **Monitor usage:**
   - Railway dashboard shows resource usage
   - Free tier includes $5 credit (usually enough for small projects)

3. **Set up monitoring:**
   - Railway provides logs and metrics
   - Set up alerts if needed

## Next Steps

Once deployed:
1. ✅ Test all functionality (admin panel, user interface, API)
2. ✅ Update portfolio with live Railway URL
3. ✅ Share demo credentials with potential clients
4. ✅ Monitor first few days for any issues

---

**Need Help?** Check Railway's documentation: https://docs.railway.app

