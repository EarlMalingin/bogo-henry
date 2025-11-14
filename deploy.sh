#!/bin/bash

# MentorHub Deployment Script
# This script helps with common deployment tasks

set -e

echo "🚀 MentorHub Deployment Script"
echo "================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env file exists
if [ ! -f .env ]; then
    echo -e "${RED}❌ Error: .env file not found!${NC}"
    echo "Creating .env from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${YELLOW}⚠️  Please configure your .env file before continuing${NC}"
        exit 1
    else
        echo -e "${RED}❌ .env.example not found either!${NC}"
        exit 1
    fi
fi

echo -e "${GREEN}✅ .env file found${NC}"

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}⚠️  APP_KEY not set, generating...${NC}"
    php artisan key:generate
fi

# Install PHP dependencies
echo ""
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo ""
echo "📦 Installing Node dependencies..."
npm install --production

# Build frontend assets
echo ""
echo "🔨 Building frontend assets..."
npm run build

# Set permissions
echo ""
echo "🔐 Setting file permissions..."
if [ -d storage ]; then
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    echo -e "${GREEN}✅ Permissions set${NC}"
fi

# Create storage link
echo ""
echo "🔗 Creating storage link..."
php artisan storage:link || echo -e "${YELLOW}⚠️  Storage link may already exist${NC}"

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
read -p "Run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

# Clear and cache config
echo ""
echo "⚡ Optimizing Laravel..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo -e "${GREEN}✅ Laravel optimization complete!${NC}"

# Socket server setup
echo ""
echo "🔌 Socket Server Setup"
echo "======================"
read -p "Start socket server with PM2? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    # Check if PM2 is installed
    if ! command -v pm2 &> /dev/null; then
        echo -e "${YELLOW}⚠️  PM2 not found. Installing globally...${NC}"
        npm install -g pm2
    fi
    
    # Stop existing instance if running
    pm2 stop mentorhub-socket 2>/dev/null || true
    pm2 delete mentorhub-socket 2>/dev/null || true
    
    # Start with production config
    pm2 start ecosystem.config.js --env production
    pm2 save
    
    echo -e "${GREEN}✅ Socket server started with PM2${NC}"
    echo "Check status with: pm2 status"
    echo "View logs with: pm2 logs mentorhub-socket"
fi

echo ""
echo -e "${GREEN}🎉 Deployment script completed!${NC}"
echo ""
echo "Next steps:"
echo "1. Verify your .env file has correct production settings"
echo "2. Ensure database is accessible"
echo "3. Test the application"
echo "4. Configure your web server (Nginx/Apache)"
echo "5. Set up SSL certificate"
echo "6. Configure firewall rules"

