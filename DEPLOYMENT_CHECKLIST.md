# 🚀 Deployment Checklist for Hostinger

## ✅ Database Status
- ✅ All test data cleared
- ✅ Database structure intact
- ✅ Essential data (achievements) seeded
- ✅ Cache cleared

## 📋 Pre-Deployment Checklist

### 1. **Environment Configuration**
- [ ] Update `.env` file with production settings:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://yourdomain.com
  
  DB_CONNECTION=mysql
  DB_HOST=your_hostinger_db_host
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_database_user
  DB_PASSWORD=your_database_password
  ```

### 2. **File Uploads & Storage**
- [ ] Ensure `storage` folder is writable (chmod 755)
- [ ] Create symbolic link: `php artisan storage:link`
- [ ] Check `public/storage` exists and is accessible

### 3. **Database Migration**
- [ ] Run migrations on production:
  ```bash
  php artisan migrate --force
  ```
- [ ] Seed achievements:
  ```bash
  php artisan db:seed --class=AchievementSeeder --force
  ```

### 4. **Cache & Optimization**
- [ ] Clear all caches:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan optimize
  ```

### 5. **File Permissions**
- [ ] Set correct permissions:
  ```bash
  chmod -R 755 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```

### 6. **Security**
- [ ] Generate new application key (if needed):
  ```bash
  php artisan key:generate
  ```
- [ ] Ensure `.env` file is not publicly accessible
- [ ] Check `APP_DEBUG=false` in production

### 7. **SSL/HTTPS**
- [ ] Enable SSL certificate on Hostinger
- [ ] Update `APP_URL` to use `https://`
- [ ] Force HTTPS in `.htaccess` (if using Apache)

## 📤 Files to Upload

### Required Files:
- ✅ All application files (except those in `.gitignore`)
- ✅ `.env` file (create new one on server with production values)
- ✅ `vendor/` folder (or run `composer install` on server)
- ✅ `node_modules/` (if using npm assets, or run `npm install` on server)

### Files to EXCLUDE (don't upload):
- ❌ `.env.example` (keep local only)
- ❌ `.git/` folder
- ❌ `node_modules/` (can reinstall on server)
- ❌ Test files
- ❌ IDE configuration files

## 🔧 Hostinger-Specific Steps

### 1. **Upload Files via FTP/File Manager**
- Upload all files to `public_html` or your domain folder
- Ensure Laravel structure is maintained

### 2. **Set Document Root**
- Point document root to `public` folder
- In Hostinger: Domain → Advanced → Document Root → Set to `/public_html/public`

### 3. **Database Setup**
- Create database in Hostinger control panel
- Note down: host, database name, username, password
- Update `.env` with these credentials

### 4. **PHP Configuration**
- Ensure PHP version is 8.2 or higher
- Enable required extensions:
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `fileinfo`

### 5. **Run Commands via SSH**
If Hostinger provides SSH access:
```bash
cd /home/username/public_html
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=AchievementSeeder --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. **Or via Hostinger Terminal**
- Access Terminal in Hostinger control panel
- Run the same commands as above

## 🧪 Post-Deployment Testing

### Test These Features:
- [ ] User registration (Student & Tutor)
- [ ] Login functionality
- [ ] Dashboard loads correctly
- [ ] Streak system displays
- [ ] Achievements page works
- [ ] Activity creation (tutor)
- [ ] Activity submission (student)
- [ ] File uploads work
- [ ] Email sending works (if configured)

## 🔄 If Something Goes Wrong

### Rollback Steps:
1. Restore database backup
2. Revert file changes
3. Clear cache: `php artisan cache:clear`
4. Check error logs: `storage/logs/laravel.log`

### Common Issues:
- **500 Error**: Check file permissions and `.env` configuration
- **Database Connection Error**: Verify database credentials in `.env`
- **Route Not Found**: Run `php artisan route:cache`
- **Storage Issues**: Run `php artisan storage:link`

## 📝 Important Notes

1. **Never commit `.env` file** to version control
2. **Backup database** before deployment
3. **Test in staging** environment first (if available)
4. **Monitor error logs** after deployment
5. **Keep local `.env` separate** from production

## 🎯 Quick Deployment Commands

```bash
# On production server
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=AchievementSeeder --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
php artisan storage:link
```

## ✅ Final Checklist

- [ ] All files uploaded
- [ ] `.env` configured correctly
- [ ] Database migrated
- [ ] Achievements seeded
- [ ] Cache optimized
- [ ] Storage linked
- [ ] Permissions set
- [ ] SSL enabled
- [ ] Tested all features
- [ ] Error logs checked

---

**Good luck with your deployment! 🚀**
