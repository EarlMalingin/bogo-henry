# Find Your Laravel Project Directory on Hostinger

## Quick Fix: Find Where Your Files Are

Since `public_html` doesn't exist in your home directory, let's find where your Laravel project is located.

### Step 1: List Files in Current Directory

Type this command to see what's in your home directory:

```bash
ls -la
```

### Step 2: Common Locations to Check

Try these common paths one by one:

```bash
# Option 1: Check if it's in domains folder
cd domains
ls -la
```

If you see your domain folder, go into it:
```bash
cd yourdomain.com
ls -la
cd public_html
```

```bash
# Option 2: Check if public_html is in a different location
find ~ -name "public_html" -type d 2>/dev/null
```

```bash
# Option 3: Look for artisan file (Laravel project root)
find ~ -name "artisan" -type f 2>/dev/null
```

```bash
# Option 4: Check if files are directly in home directory
ls -la ~
```

### Step 3: Alternative - Check File Manager

If commands are confusing, use Hostinger File Manager:
1. Go to Hostinger Control Panel
2. Click **Files** → **File Manager**
3. Look for your Laravel project files
4. Note the full path shown in the address bar

### Step 4: Once You Find the Directory

Once you find where `artisan` file is located, navigate there:

```bash
cd /path/to/your/project
```

For example:
```bash
cd ~/domains/yourdomain.com/public_html
# OR
cd ~/public_html
# OR wherever artisan file is
```

### Step 5: Verify You're in the Right Place

Check if `artisan` file exists:

```bash
ls -la artisan
```

If you see the file, you're in the right place!

### Step 6: Run the Commands

Now run:

```bash
php artisan db:seed --class=AdminSeeder
php artisan config:clear
php artisan cache:clear
```

