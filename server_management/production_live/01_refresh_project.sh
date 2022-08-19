PROJECT_FOLDER="/var/www/html/TalusWebBackend"

# laravel environment
cd $PROJECT_FOLDER

# activate maintanence mode
php artisan down

# project permissions
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# only production packages (in local environment, use only 'composer install')
composer install --no-dev

php artisan clear-compiled
php artisan optimize:clear

composer cc
composer dump-autoload

php artisan optimize

# deactivate maintanence mode
php artisan up
