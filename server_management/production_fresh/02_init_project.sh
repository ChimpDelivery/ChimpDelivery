echo "\n${COLOR_CYAN}Step 2 - PROJECT INITIALIZATION${NO_COLOR}"

# create environment file in project folder
cd $PROJECT_FOLDER
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# add cron entry for scheduling in laravel
(crontab -l 2>/dev/null; echo "* * * * * cd $PROJECT_FOLDER && /usr/bin/php8.1 artisan schedule:run >> /dev/null 2>&1") | crontab -

# only production packages (in local environment, use only 'composer install')
composer install --no-dev

php artisan key:generate --force
php artisan migrate:fresh --seed --force
