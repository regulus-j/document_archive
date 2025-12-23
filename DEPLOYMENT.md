# Deployment Guide

## Nixpacks Deployment

This project includes a `nixpacks.toml` configuration file to optimize deployment on platforms that use Nixpacks (such as Railway, Render, or similar services).

### Configuration Overview

The nixpacks configuration addresses common timeout issues by:

1. **Explicit Dependencies**: Specifies exact versions of PHP (8.2) and Node.js (20)
2. **System Packages**: Includes required system dependencies:
   - `tesseract`: For OCR functionality
   - `poppler_utils`: For PDF processing
3. **Optimized Installation**: Uses production-optimized flags for faster installs:
   - `composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist`
   - `npm ci --prefer-offline --no-audit`
4. **Efficient Build**: Only builds necessary assets with Vite

### Deployment Process

The deployment follows these phases:

1. **Setup Phase**: Installs PHP, Composer, Node.js, Tesseract, and Poppler utilities
2. **Install Phase**: Installs PHP and Node.js dependencies
3. **Build Phase**: Compiles frontend assets using Vite
4. **Start Phase**: Runs the Laravel application server

### Environment Variables

Make sure to set the following environment variables in your deployment platform:

- `APP_KEY`: Laravel application key (generate with `php artisan key:generate` in a secure environment. **CRITICAL**: This key is used for encryption and must be kept secret. Never log or expose it.)
- `APP_ENV`: Set to `production`
- `APP_DEBUG`: Set to `false` for production
- `DB_CONNECTION`: Database connection type
- `DB_HOST`: Database host
- `DB_PORT`: Database port
- `DB_DATABASE`: Database name
- `DB_USERNAME`: Database username
- `DB_PASSWORD`: Database password

### Procfile

The included `Procfile` handles:
- Running database migrations on startup
- Caching configuration, routes, and views for better performance
- Starting the application server

### Troubleshooting Timeouts

If you still experience timeout issues:

1. **Increase Build Timeout**: In your platform settings, increase the build timeout to at least 10-15 minutes
2. **Check Logs**: Review build logs to identify which phase is timing out
3. **Optimize Dependencies**: Consider removing unused dependencies from `composer.json` and `package.json`
4. **Use Build Cache**: Ensure your platform has caching enabled for faster subsequent builds

### Platform-Specific Notes

#### Railway
- Railway automatically detects the `nixpacks.toml` file
- Build timeout can be adjusted in project settings
- Ensure you have enough allocated resources for the build

#### Render
- Render also supports nixpacks configuration
- You may need to select "Docker" as the environment type
- Build instance size can be upgraded for faster builds

## Manual Deployment

If deploying manually without Nixpacks:

1. Install system dependencies: `sudo apt-get install tesseract-ocr poppler-utils`
2. Install PHP dependencies: `composer install --no-dev --optimize-autoloader`
3. Install Node dependencies: `npm ci`
4. Build assets: `npm run build`
5. Set up environment: Copy `.env.example` to `.env` and configure
6. Generate key: `php artisan key:generate`
7. Run migrations: `php artisan migrate --force`
8. Cache configuration: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
9. Start server: `php artisan serve --host=0.0.0.0 --port=8000`
