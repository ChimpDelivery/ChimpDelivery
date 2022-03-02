# Talus Web Backend
- No ssh support for current host(cPanel host), only ftp server.

# :exclamation: Requirements
- php >= 8.1x
- laravel >= 9.3

# 💿 Installation (on Local)
```
# update sudo packages
sudo apt update && sudo apt -y upgrade

# add sudo repository for php >= 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt-get install tasksel
sudo tasksel install lamp-server
sudo mysql_secure_installation

# install php8.1
sudo apt install php8.1

# install php8.1 packages
sudo apt-get install php8.1-curl
sudo apt-get install php8.1-mysql

# restart apache && mysql
sudo service apache2 restart
sudo serivce mysql restart

cp .env.example .env
composer update
php artisan serve
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
