# Talus Web Backend

# 💿 Installation (on Local)
```
# update sudo packages
sudo apt update && sudo apt -y upgrade
sudo apt-get install software-properties-common

# add sudo repository for php >= 8.1
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# install lamp stack
sudo apt-get install tasksel
sudo tasksel install lamp-server
sudo mysql_secure_installation

# install php8.1
sudo apt install php8.1

# install php8.1 packages
sudo apt-get install php8.1-curl
sudo apt-get install php8.1-mysql
sudo apt-get install php8.1-mbstring
sudo apt-get install php8.1-xml
sudo apt-get install zip unzip php8.1-zip

# install composer
https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04
sudo mv /usr/local/bin/composer /usr/bin

# restart apache && mysql
sudo service apache2 restart
sudo service mysql stop
sudo usermod -d /var/lib/mysql/ mysql
sudo service mysql start
sudo mysql_secure_installation

cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

# OpenSSL Issue
``` 
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

- Recommended REST client https://www.postman.com 

# 🔑 Talus App Api - Endpoints
```
GET  |  api/appinfo/{db_id}
```

# 🔑 Talus AppStoreConnect Api - Endpoints
```
GET  |  api/appstoreconnect/get-token
GET  |  api/appstoreconnect/get-full-info
GET  |  api/appstoreconnect/get-app-list
GET  |  api/appstoreconnect/get-app-dictionary
GET  |  api/appstoreconnect/get-all-bundles
GET  |  api/appstoreconnect/clear-cache
```