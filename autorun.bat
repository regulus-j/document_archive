@echo off
start cmd /k "php artisan serve"
start "CMD2" cmd /k "npm run dev"
exit
