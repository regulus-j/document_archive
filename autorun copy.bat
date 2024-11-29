@echo off

php artisan migrate:fresh


php artisan db:seed --class=PermissionTableSeeder


php artisan db:seed --class=CreateAdminUserSeeder

php artisan db:seed --class=TeamSeeder


:: End of the batch script
exit
