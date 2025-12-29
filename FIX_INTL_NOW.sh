#!/bin/bash

echo "=========================================="
echo "  FIXING INTL EXTENSION - QUICK FIX"
echo "=========================================="
echo ""
echo "This will take about 2-3 minutes."
echo ""

# Fix Homebrew permissions
echo "Step 1: Fixing Homebrew permissions..."
sudo chown -R $(whoami) /opt/homebrew

echo ""
echo "Step 2: Reinstalling PHP with intl extension..."
brew reinstall php

echo ""
echo "Step 3: Verifying intl is now loaded..."
php -m | grep intl

if php -m | grep -q intl; then
    echo ""
    echo "✅ SUCCESS! intl extension is now installed!"
    echo ""
    echo "Now restart your PHP server:"
    echo "  1. Stop php artisan serve (Ctrl+C)"
    echo "  2. Run: cd /Users/user/Documents/GitHub/Tyrese-PWA"
    echo "  3. Run: php artisan serve"
    echo ""
    echo "Then refresh your admin panel in the browser!"
else
    echo ""
    echo "❌ intl still not loaded. Manual fix needed."
fi

