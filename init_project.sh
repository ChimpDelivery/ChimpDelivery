export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8
export LANGUAGE=en_US.UTF-8

# save project folder path
PROJECT_FOLDER="/var/www/html/TalusWebBackend"

# laravel environment
cd $PROJECT_FOLDER
composer install
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate --force
fi
php artisan migrate --force
php artisan clear-compiled
php artisan optimize:clear

composer cc
composer dump-autoload

php artisan optimize
