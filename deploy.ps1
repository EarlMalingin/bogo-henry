# MentorHub Deployment Script for Windows
# This script helps with common deployment tasks

Write-Host "🚀 MentorHub Deployment Script" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env file exists
if (-not (Test-Path .env)) {
    Write-Host "❌ Error: .env file not found!" -ForegroundColor Red
    Write-Host "Creating .env from .env.example..." -ForegroundColor Yellow
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
        Write-Host "⚠️  Please configure your .env file before continuing" -ForegroundColor Yellow
        exit 1
    } else {
        Write-Host "❌ .env.example not found either!" -ForegroundColor Red
        exit 1
    }
}

Write-Host "✅ .env file found" -ForegroundColor Green

# Check if APP_KEY is set
$envContent = Get-Content .env -Raw
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "⚠️  APP_KEY not set, generating..." -ForegroundColor Yellow
    php artisan key:generate
}

# Install PHP dependencies
Write-Host ""
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Cyan
composer install --optimize-autoloader --no-dev

# Install Node dependencies
Write-Host ""
Write-Host "📦 Installing Node dependencies..." -ForegroundColor Cyan
npm install --production

# Build frontend assets
Write-Host ""
Write-Host "🔨 Building frontend assets..." -ForegroundColor Cyan
npm run build

# Set permissions (Windows doesn't need chmod, but we'll note it)
Write-Host ""
Write-Host "🔐 File permissions..." -ForegroundColor Cyan
Write-Host "✅ Windows handles permissions automatically" -ForegroundColor Green

# Create storage link
Write-Host ""
Write-Host "🔗 Creating storage link..." -ForegroundColor Cyan
php artisan storage:link
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Storage link may already exist" -ForegroundColor Yellow
}

# Run migrations
Write-Host ""
Write-Host "🗄️  Running database migrations..." -ForegroundColor Cyan
$runMigrations = Read-Host "Run migrations? (y/n)"
if ($runMigrations -eq "y" -or $runMigrations -eq "Y") {
    php artisan migrate --force
}

# Clear and cache config
Write-Host ""
Write-Host "⚡ Optimizing Laravel..." -ForegroundColor Cyan
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host ""
Write-Host "✅ Laravel optimization complete!" -ForegroundColor Green

# Socket server setup
Write-Host ""
Write-Host "🔌 Socket Server Setup" -ForegroundColor Cyan
Write-Host "======================" -ForegroundColor Cyan
$startSocket = Read-Host "Start socket server with PM2? (y/n)"
if ($startSocket -eq "y" -or $startSocket -eq "Y") {
    # Check if PM2 is installed
    $pm2Installed = Get-Command pm2 -ErrorAction SilentlyContinue
    if (-not $pm2Installed) {
        Write-Host "⚠️  PM2 not found. Installing globally..." -ForegroundColor Yellow
        npm install -g pm2
    }
    
    # Stop existing instance if running
    pm2 stop mentorhub-socket 2>$null
    pm2 delete mentorhub-socket 2>$null
    
    # Start with production config
    pm2 start ecosystem.config.js --env production
    pm2 save
    
    Write-Host "✅ Socket server started with PM2" -ForegroundColor Green
    Write-Host "Check status with: pm2 status"
    Write-Host "View logs with: pm2 logs mentorhub-socket"
}

Write-Host ""
Write-Host "🎉 Deployment script completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:"
Write-Host "1. Verify your .env file has correct production settings"
Write-Host "2. Ensure database is accessible"
Write-Host "3. Test the application"
Write-Host "4. Configure your web server (IIS/Nginx/Apache)"
Write-Host "5. Set up SSL certificate"
Write-Host "6. Configure firewall rules"

