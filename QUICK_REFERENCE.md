# Quick Reference Card

## 🚀 Reset Database with Dummy Data

```bash
php artisan db:demo
```

## 🔐 Demo Login

```
Email: admin@demo.com
Password: demo123
```

## 💻 Start Dev Servers

```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend  
npm run dev

# Terminal 3 - Portfolio (optional)
cd /Users/user/Documents/GitHub/tyrese-portfolio && npm run dev
```

## 📸 Required Screenshots

1. `sokofresh-dashboard.png` - Dashboard overview
2. `sokofresh-inventory.png` - Inventory list
3. `sokofresh-visits.png` - Visit scheduling
4. `sokofresh-labourers.png` - Labour management

**Save to:** `/Users/user/Documents/GitHub/tyrese-portfolio/public/`

## 🎥 Video (Optional)

Record 2-3 min walkthrough → Save as `sokofresh-demo.mp4`

## 📚 Detailed Guides

- **Complete Overview:** `PORTFOLIO_PREP_SUMMARY.md`
- **Screenshot Tips:** `SCREENSHOT_GUIDE.md`
- **Dummy Data Info:** `DEMO_DATA_GUIDE.md`

## 🐛 Quick Fixes

**Seeder error?**
```bash
composer dump-autoload && php artisan db:demo
```

**Foreign key error?**
```bash
php artisan migrate:fresh && php artisan db:demo
```

**Portfolio images not loading?**
- Check filenames match exactly
- Clear Next.js cache: `rm -rf .next`

