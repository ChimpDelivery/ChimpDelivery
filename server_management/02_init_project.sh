export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8
export LANGUAGE=en_US.UTF-8

# save project folder path
PROJECT_FOLDER="/var/www/html/TalusWebBackend"

# create environment file in project folder
cd $PROJECT_FOLDER
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# only production packages (in local environment, use only 'composer install')
composer install --no-dev

php artisan key:generate --force
php artisan migrate:fresh --seed --force
