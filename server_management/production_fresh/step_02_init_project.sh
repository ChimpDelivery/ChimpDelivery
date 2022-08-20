cd $1

# if environment file already exists, skip this step
if [ ! -f ".env" ]; then
    cp .env.example .env

    # add cron entry for scheduling in laravel
    (crontab -l 2>/dev/null; echo "* * * * * cd $1 && /usr/bin/php8.1 artisan schedule:run >> /dev/null 2>&1") | crontab -

    # only production packages (in local environment, use only 'composer install')
    composer install --no-dev

    php artisan key:generate --force
    php artisan migrate:fresh --seed --force
else
    echo "\n Step - 2: Environment file already exists, skipping this step...\n"
fi
