# Demo Data Setup Guide

This guide explains how to prepare the Sokofresh system with dummy data for screenshots, demos, and portfolio presentations.

## ⚠️ Important

This process will **DELETE ALL EXISTING DATA** and replace it with privacy-safe dummy data. Only use this in a development/demo environment, never in production!

## Quick Start

### 1. Reset Database with Dummy Data

Run the following command:

```bash
php artisan db:demo
```

You will be prompted to confirm before any data is deleted. To skip the confirmation (useful for scripts):

```bash
php artisan db:demo --force
```

### 2. Login Credentials

After running the command, you can log in with these demo accounts:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@demo.com | demo123 |
| Manager | manager@demo.com | demo123 |
| Technician | tech@demo.com | demo123 |

## What Gets Created

The dummy data seeder creates:

### Users
- 1 Admin user
- 2 Manager users
- 3 Technician users

All with safe, demo email addresses and simple passwords.

### Locations & Infrastructure
- 6 Hubs with generic location names (North Hub, South Hub, etc.)
- 8 Cold Storage Units spread across the hubs
- Realistic but fake coordinates

### Inventory
- 7 Tools (wrenches, drills, etc.)
- 8 Spare Parts (compressor belts, sensors, etc.)
- 3 Assets (generators, safety kits, etc.)

### Operations Data
- 4 Batteries installed in various units
- 20 Maintenance visits (past and upcoming)
- 30 Inventory actions (check-ins, transfers, etc.)
- 40 Crate movements between hubs

### Personnel
- 15 Casual labourers with fake names and contact info
- 30 days of attendance records

## Manual Seeding (Alternative Method)

If you prefer to run the seeders manually:

```bash
# Clear the database
php artisan db:seed --class=ClearDatabaseSeeder

# Populate with dummy data
php artisan db:seed --class=DummyDataSeeder
```

## Reverting to Real Data

If you need to restore your actual production data:

1. Make sure you have a recent database backup
2. Drop all tables
3. Restore from your backup

**Always maintain regular backups before using dummy data!**

## Using for Screenshots/Videos

Once you've populated the dummy data:

1. Start your development server:
   ```bash
   php artisan serve
   npm run dev
   ```

2. Log in with one of the demo accounts

3. Navigate through the different features:
   - Dashboard overview
   - Inventory management
   - Visit scheduling
   - Labourer attendance
   - Hub operations
   - Battery monitoring

4. Take screenshots/record videos as needed

5. All data shown will be privacy-safe dummy data

## Data Privacy Compliance

This dummy data system ensures:

✅ No real personal information (names, phone numbers, ID numbers)  
✅ No real location data  
✅ No actual business metrics  
✅ No confidential company information  
✅ Safe for public portfolio/demo use  

## Troubleshooting

### "Class not found" errors

Run composer autoload:
```bash
composer dump-autoload
```

### Foreign key constraint errors

Make sure all migrations are up to date:
```bash
php artisan migrate:fresh
php artisan db:demo
```

### Need different data?

Edit `/database/seeders/DummyDataSeeder.php` to customize:
- Number of records
- Dummy names and values
- Date ranges
- Status distributions

## Files Involved

- `/database/seeders/DummyDataSeeder.php` - Main dummy data seeder
- `/database/seeders/ClearDatabaseSeeder.php` - Database clearing utility
- `/app/Console/Commands/ResetWithDummyData.php` - Artisan command

