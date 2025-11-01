#!/bin/bash

# WooCommerce Integration - Quick Start Script
# تاريخ: 26 أكتوبر 2025

echo "🛒 Huda ERP - WooCommerce Integration"
echo "======================================"
echo ""

# Change to project directory
cd "/Users/yousefgamal/Desktop/myproject/hudaalpinejs/huda-erp-laravel"

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    exit 1
fi

# Check WooCommerce config
echo "📋 Checking WooCommerce Configuration..."
echo ""

STORE_URL=$(grep WOOCOMMERCE_STORE_URL .env | cut -d '=' -f2)
CONSUMER_KEY=$(grep WOOCOMMERCE_CONSUMER_KEY .env | cut -d '=' -f2)

if [ "$STORE_URL" = "https://your-store.com/" ]; then
    echo "⚠️  WARNING: WooCommerce Store URL is not configured!"
    echo ""
    echo "Please update the following in your .env file:"
    echo "  WOOCOMMERCE_STORE_URL=https://your-actual-store.com/"
    echo "  WOOCOMMERCE_CONSUMER_KEY=ck_your_key_here"
    echo "  WOOCOMMERCE_CONSUMER_SECRET=cs_your_secret_here"
    echo ""
    echo "📖 See WOOCOMMERCE_SETUP_AR.md for detailed instructions"
    exit 1
fi

echo "✅ Store URL: $STORE_URL"
echo "✅ Consumer Key: ${CONSUMER_KEY:0:10}..."
echo ""

# Clear cache
echo "🧹 Clearing cache..."
php artisan optimize:clear > /dev/null 2>&1
echo "✅ Cache cleared"
echo ""

# Test WooCommerce sync
echo "🔄 Testing WooCommerce Sync..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
php artisan woocommerce:sync
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Ask if user wants to start scheduler
read -p "🔄 Do you want to start the scheduler? (runs sync every 5 minutes) [y/N]: " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo "🚀 Starting Laravel Scheduler..."
    echo "   Press Ctrl+C to stop"
    echo ""
    php artisan schedule:work
else
    echo ""
    echo "ℹ️  You can manually run sync anytime with:"
    echo "   php artisan woocommerce:sync"
    echo ""
    echo "ℹ️  Or start the scheduler with:"
    echo "   php artisan schedule:work"
    echo ""
fi

echo ""
echo "✅ Setup Complete!"
echo "📊 View orders at: http://127.0.0.1:8000/orders"
echo ""

