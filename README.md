# Talus Web Backend
- Production server running on AWS Lightsail.
- Ngrok Tunnel has to be opened on Build Mac. (There is a parameter ```JENKINS_HOST``` on .env file)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)
- [Postman](https://www.postman.com)

# 💿 Environment Setup
- Required OS >= Ubuntu 20.04
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

# install php8.1
sudo apt install php8.1

# install php8.1 packages
sudo apt-get install php8.1-curl
sudo apt-get install php8.1-mysql
sudo apt-get install php8.1-mbstring
sudo apt-get install php8.1-xml
sudo apt-get install zip unzip php8.1-zip

# install composer
cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer

# restart apache && mysql
sudo service apache2 restart
sudo service mysql stop
sudo usermod -d /var/lib/mysql/ mysql
sudo service mysql start
sudo mysql_secure_installation

# start cron and initialize it
sudo service cron start
crontab -e
* * * * * cd /var/www/html/TalusWebBackend && /usr/bin/php8.1 artisan schedule:run >> /dev/null 2>&1

cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan optimize
```

# 💿 Production Server - Apache
- enable mod_rewrite ```sudo a2enmod rewrite```
- edit ```/etc/apache2/apache2.conf```
```
  <Directory /var/www/>
      Options Indexes FollowSymLinks
      AllowOverride all
      Require all granted
  </Directory>
```
- set ```DocumentRoot``` in ```/etc/apache2/sites-enabled/000-default.conf```
- ```sudo service apache2 restart```

# 🔑 AppStoreConnect Api - Endpoints
```
GET  |  api/appstoreconnect/get-token
GET  |  api/appstoreconnect/get-full-info
GET  |  api/appstoreconnect/get-app-list
GET  |  api/appstoreconnect/get-app-list/{id}
GET  |  api/appstoreconnect/create-bundle?bundle_id={bundleId}&bundle_name={bundleName}
```


# 🔑 Jenkins Api - Endpoints
1. [Jenkins REST API - Documentation](https://github.com/jenkinsci/pipeline-stage-view-plugin/tree/master/rest-api)
```
GET  |  api/jenkins/get-job-list
GET  |  api/jenkins/get-job/{projectName}
GET  |  api/jenkins/get-build-list/{projectName}
GET  |  api/jenkins/get-latest-build-info/{projectName}
GET  |  api/jenkins/stop-job/{projectName}/{buildNumber}
```

# 🔑 GitHub Api - Endpoints
```
GET  |  api/github/get-repositories
GET  |  api/github/get-repository/{id}
```
