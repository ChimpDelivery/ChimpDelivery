export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8
export LANGUAGE=en_US.UTF-8

# save project folder path
PROJECT_FOLDER="/var/www/html/TalusWebBackend"

# laravel environment
cd $PROJECT_FOLDER

# project permissions
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# only production packages (in local environment, use only 'composer install')
composer install --no-dev

php artisan key:generate --force
php artisan migrate --force
php artisan clear-compiled
php artisan optimize:clear

composer cc
composer dump-autoload

php artisan optimize
