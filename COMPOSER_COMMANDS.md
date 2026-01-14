# Composer Commands for Server Deployment

## Step 1: Navigate to the Laravel project directory
```bash
cd /path/to/Rolla_backend-Laravel
```

## Step 2: Install/Update the required package
Since the code now uses AWS SDK directly, you have two options:

### Option A: Install the flysystem package (recommended for future compatibility)
```bash
composer require league/flysystem-aws-s3-v3
```

### Option B: If Option A fails, just update all dependencies
```bash
composer update
```

## Step 3: Clear Laravel caches (important!)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Step 4: Optimize (optional but recommended for production)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Alternative: If composer update fails, try this sequence:

### 1. Remove vendor directory and composer.lock (if needed)
```bash
rm -rf vendor
rm composer.lock
```

### 2. Fresh install
```bash
composer install --no-interaction
```

### 3. Clear caches
```bash
php artisan config:clear
php artisan cache:clear
```

## Full Command Sequence (Copy and paste all at once)
```bash
cd /path/to/Rolla_backend-Laravel && \
composer require league/flysystem-aws-s3-v3 && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan route:clear && \
php artisan view:clear
```

## Note:
- Replace `/path/to/Rolla_backend-Laravel` with your actual server path
- Make sure you have proper permissions to run composer commands
- If you're using a shared hosting, you might need to use `php composer.phar` instead of `composer`
