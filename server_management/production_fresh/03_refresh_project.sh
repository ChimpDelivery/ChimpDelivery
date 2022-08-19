echo "\n${COLOR_CYAN}Step 3 - PROJECT REFRESHING${NO_COLOR}"

# laravel environment
cd $PROJECT_FOLDER

# project permissions
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# only production packages (in local environment, use only 'composer install')
composer install # --no-dev

php artisan clear-compiled
php artisan optimize:clear

composer cc
composer dump-autoload

php artisan optimize

# display success
echo "\n${COLOR_GREEN}Success ! Project initialized!${NO_COLOR}"

# unset created environment variables after all process completed
unset PROJECT_FOLDER
unset COLOR_RED
unset COLOR_GREEN
unset COLOR_YELLOW
unset COLOR_CYAN
unset NO_COLOR
