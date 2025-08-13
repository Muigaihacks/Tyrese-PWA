#!/bin/bash

echo "🚀 Setting up Sokofresh Inventory System..."

# Check if .env exists, if not copy from example
if [ ! -f .env ]; then
    echo "📝 Creating .env file from example..."
    cp .env.example .env
    echo "⚠️  Please update your .env file with your database and mail settings!"
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Generate app key if not already set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

echo "✅ Setup complete! You can now run:"
echo "   Terminal 1: php artisan serve"
echo "   Terminal 2: npm run dev"
