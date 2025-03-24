@echo off

php artisan migrate:fresh

php artisan db:seed
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=OfficeCompanyUser
php artisan db:seed --class=CreateAdminUserSeeder

:: End of the batch script
exit
