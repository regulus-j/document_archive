@echo off
start cmd /k "php artisan serve"
timeout /t 10
start "CMD2" cmd /k "npm run dev"
exit
