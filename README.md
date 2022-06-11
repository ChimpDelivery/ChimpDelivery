# Talus Web Backend
- Production server running on AWS Lightsail.
- Ngrok Tunnel has to be opened on Build Mac. (There is a parameter ```JENKINS_HOST``` on .env file)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)
- [Postman](https://www.postman.com)

# 💿 Environment Setup
- Required OS >= Ubuntu 20.04
# 1. Update Packages
```
sudo apt update && sudo apt -y upgrade && \
sudo apt-get install software-properties-common -y
```

# 2. Repository for php >= 8.1
```
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

# 3. Install LAMP Stack
```
sudo apt-get install tasksel -y
sudo tasksel install lamp-server
```

# 4. Install PHP8.1 Related Packages
```
sudo apt-get install php8.1 -y && \
sudo apt-get install redis-server -y && \
sudo apt-get install php8.1-curl php8.1-mysql php8.1-mbstring php8.1-xml -y && \
sudo apt-get install zip unzip php8.1-zip -y
```

# 5. Install Composer
```
cd ~ && \
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
HASH=`curl -sS https://composer.github.io/installer.sig` && \
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
sudo php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer
```

# 6. Crontab Settings
crontab -e
```
* * * * * cd /var/www/html/TalusWebBackend && /usr/bin/php8.1 artisan schedule:run >> /dev/null 2>&1
```

# 7. MySQL Settings
```
change root password
create database laravel
```

# 8. Restart Services
```
sudo service apache2 restart
sudo service cron restart
sudo service redis-server restart
sudo service mysql restart
sudo usermod -d /var/lib/mysql/ mysql
```

# 9. Laravel Settings
```
cd /var/www/html/TalusWebBackend && \
sudo chown -R www-data:www-data storage && \
sudo chown -R www-data:www-data bootstrap/cache && \
sudo chmod -R 775 storage && \
sudo chmod -R 775 bootstrap/cache && \
composer install && \
cp .env.example .env && \
php artisan key:generate && \
php artisan migrate && \
php artisan optimize
```

# 10. Apache Settings
- enable mod_rewrite ```sudo a2enmod rewrite```
- edit ```/etc/apache2/apache2.conf```
```
  <Directory /var/www/>
      Options Indexes FollowSymLinks
      AllowOverride all
      Require all granted
  </Directory>
```
- set ```DocumentRoot``` path in ```/etc/apache2/sites-enabled/000-default.conf``` with ```/var/www/html/TalusWebBackend/public```
- and finally, run ```sudo service apache2 restart```

# 🔑 AppStoreConnect API
```
GET  |  api/appstoreconnect/get-token
GET  |  api/appstoreconnect/get-full-info
GET  |  api/appstoreconnect/get-app-list
GET  |  api/appstoreconnect/get-app-list/{id}
GET  |  api/appstoreconnect/create-bundle?bundle_id={bundleId}&bundle_name={bundleName}
```

# 🔑 [Jenkins API](https://github.com/jenkinsci/pipeline-stage-view-plugin/tree/master/rest-api)
```
GET  |  api/jenkins/get-job-list
GET  |  api/jenkins/get-job/{projectName}
GET  |  api/jenkins/get-build-list/{projectName}
GET  |  api/jenkins/get-latest-build-info/{projectName}
GET  |  api/jenkins/stop-job/{projectName}/{buildNumber}
```

# 🔑 GitHub API
```
GET  |  api/github/get-repositories
GET  |  api/github/get-repository/{id}
```

# 🔑 Apps API
```
GET  |  api/get-app/{id}
```


# 🔑 Packages API
```
GET  |  api/get-package/{id}
GET  |  api/update-package/{id}/{hash}
```
