# Talus Web Backend
- No ssh support for current host(cPanel host), only ftp server.

# 💿 Installation (on Local)
- Install XAMPP from https://www.apachefriends.org/tr/index.html and install it.
- Open XAMPP control panel and start Apache and MySQL modules.
- Clone repository and extract it.
- Create ".env" file from ".env.example" template in project folder.
- Populate ".env" contents.
- Open bash/powershell etc. in project folder.

```
# update sudo packages
sudo apt update && sudo apt -y upgrade

# add sudo repository for php >= 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt-get install tasksel
sudo tasksel install lamp-server

# install php8.1
sudo apt install php8.1

# install php8.1 packages
sudo apt-get install php8.1-curl
sudo apt-get install php8.1-mysql
sudo apt-get install composer

# restart apache && mysql
sudo service apache2 restart
sudo service mysql stop
sudo usermod -d /var/lib/mysql/ mysql
sudo service mysql start
sudo mysql_secure_installation

cp .env.example .env
composer update
php artisan serve
```

- Recommended FTP client https://winscp.net/eng/download.php (only Windows)
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
