#!/bin/bash

# Enable PHP intl extension
echo "Enabling PHP intl extension..."

sudo sed -i '' 's/;extension=intl/extension=intl/' /opt/homebrew/etc/php/8.4/php.ini

echo ""
echo "✅ intl extension enabled!"
echo ""
echo "Now restart your PHP server:"
echo "  1. Stop php artisan serve (Ctrl+C)"
echo "  2. Start it again: php artisan serve"
echo ""
echo "Verify it worked:"
php -m | grep intl && echo "✅ intl is now loaded!" || echo "❌ intl still not loaded"

