# MentorHub Real-Time Chat Deployment Guide

## 🚀 Deployment Readiness Assessment

### ✅ **Ready for Deployment**
- ✅ Socket.IO server running on port 3001
- ✅ Livewire chat components integrated
- ✅ Database models and relationships configured
- ✅ User authentication system in place
- ✅ File upload functionality working
- ✅ Real-time message broadcasting
- ✅ Typing indicators
- ✅ Read receipts
- ✅ Search functionality
- ✅ Responsive UI design

### 📋 **Pre-Deployment Checklist**

#### 1. **Socket Server Configuration**
- [ ] Socket server is running on production port
- [ ] CORS configured for production domain
- [ ] Environment variables set
- [ ] PM2 or similar process manager configured
- [ ] SSL certificate installed (for HTTPS)

#### 2. **Laravel Application**
- [ ] Database migrations run
- [ ] Storage links created (`php artisan storage:link`)
- [ ] Environment variables configured
- [ ] Queue workers running (if using queues)
- [ ] File permissions set correctly

#### 3. **Frontend Assets**
- [ ] Socket.IO client scripts included
- [ ] User authentication data passed to frontend
- [ ] CSS and JS files compiled
- [ ] CDN or local asset serving configured

## 🛠 **Deployment Steps**

### Step 1: Server Setup

```bash
# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install PM2 globally
sudo npm install -g pm2

# Install dependencies
npm install
```

### Step 2: Socket Server Deployment

```bash
# Start socket server with PM2
pm2 start ecosystem.config.js --env production

# Save PM2 configuration
pm2 save

# Set PM2 to start on boot
pm2 startup
```

### Step 3: Laravel Deployment

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear and cache config
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Environment Configuration

Create `.env` file with production settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com
```

### Step 5: Web Server Configuration

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    root /var/www/mentorhub/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Socket.IO proxy
    location /socket.io/ {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### Step 6: Security Configuration

```bash
# Set up firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable

# Install SSL certificate (Let's Encrypt)
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

## 🔧 **Testing Your Deployment**

### 1. **Socket Server Test**
```bash
# Check if socket server is running
curl http://localhost:3001/health

# Check PM2 status
pm2 status
pm2 logs mentorhub-socket
```

### 2. **Laravel Application Test**
```bash
# Test Laravel routes
php artisan route:list

# Check storage permissions
ls -la storage/
ls -la bootstrap/cache/
```

### 3. **Real-Time Chat Test**
1. Open two browser windows
2. Log in as a student in one window
3. Log in as a tutor in another window
4. Navigate to messages in both windows
5. Send messages between them
6. Test file uploads
7. Test typing indicators

## 📊 **Monitoring & Maintenance**

### Health Checks
```bash
# Socket server health
curl https://yourdomain.com:3001/health

# Laravel application health
curl https://yourdomain.com/up
```

### Logs
```bash
# Socket server logs
pm2 logs mentorhub-socket

# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

### Performance Monitoring
```bash
# Check memory usage
pm2 monit

# Check system resources
htop
```

## 🚨 **Troubleshooting**

### Common Issues

1. **Socket connection failed**
   - Check if socket server is running: `pm2 status`
   - Check firewall settings
   - Verify CORS configuration

2. **Messages not sending**
   - Check browser console for errors
   - Verify user authentication data
   - Check database connections

3. **File uploads not working**
   - Check storage permissions
   - Verify storage link: `php artisan storage:link`
   - Check file size limits

4. **Real-time updates not working**
   - Check socket server logs
   - Verify Livewire polling settings
   - Check browser network tab

## 📈 **Scaling Considerations**

### For High Traffic
1. **Load Balancing**: Use multiple socket server instances
2. **Redis**: Implement Redis for session storage
3. **Database**: Consider read replicas for chat data
4. **CDN**: Use CDN for static assets
5. **Caching**: Implement Redis caching for frequently accessed data

### Performance Optimization
1. **Message Pagination**: Implement message pagination
2. **File Compression**: Compress uploaded files
3. **Image Optimization**: Resize images before storage
4. **Database Indexing**: Add indexes for chat queries

## 🔐 **Security Best Practices**

1. **Input Validation**: Validate all user inputs
2. **File Upload Security**: Restrict file types and sizes
3. **Rate Limiting**: Implement rate limiting for messages
4. **HTTPS**: Use HTTPS for all communications
5. **Authentication**: Verify user sessions
6. **SQL Injection**: Use prepared statements
7. **XSS Protection**: Sanitize user inputs

## 📞 **Support**

If you encounter issues during deployment:
1. Check the logs for error messages
2. Verify all configuration files
3. Test each component individually
4. Review the troubleshooting section above

---

**Deployment Status**: ✅ **READY FOR DEPLOYMENT**

Your real-time chat system is now ready for production deployment! 