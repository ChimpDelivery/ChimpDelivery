export LC_ALL=en_US.UTF-8
export LANG=en_US.UTF-8

# save project folder path
PROJECT_FOLDER=$PWD

# update packages and install common packages
sudo apt update
sudo apt -y upgrade
sudo apt-get install software-properties-common -y

# add repo for >= php8.1
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# install lamp stack
sudo apt-get install tasksel -y
sudo tasksel install lamp-server

# install php8.1 related packages
sudo apt-get install php8.1 -y
sudo apt-get install php8.1-curl php8.1-mysql php8.1-mbstring php8.1-xml -y
sudo apt-get install zip unzip php8.1-zip -y

# install redis
sudo apt-get install redis-server -y

# install composer
cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer

# project permissions
cd $PROJECT_FOLDER
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# laravel environment
cd $PROJECT_FOLDER
composer install
if [ ! -f ".env" ]; then
    cp .env.example .env
fi
php artisan key:generate
php artisan migrate
php artisan optimize
