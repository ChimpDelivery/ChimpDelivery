# Talus Web Backend
- Production server running on AWS Lightsail.
- Ngrok Tunnel has to be opened on Build Mac. (There is a parameter ```JENKINS_HOST``` on .env file)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)
- [Postman](https://www.postman.com)

# 💿 Environment Setup
- Required OS >= Ubuntu 20.04
# 1. Run Script
```
sh init_server.sh
```

# 2. Crontab Settings
crontab -e
```
* * * * * cd /var/www/html/TalusWebBackend && /usr/bin/php8.1 artisan schedule:run >> /dev/null 2>&1
```

# 3. MySQL Settings
```
change root password
create database laravel
```

# 4. Apache Settings
- edit ```/etc/apache2/apache2.conf```
```
  <Directory /var/www/>
      Options Indexes FollowSymLinks
      AllowOverride all
      Require all granted
  </Directory>
```
- set ```DocumentRoot``` path in ```/etc/apache2/sites-enabled/000-default.conf``` with ```/var/www/html/TalusWebBackend/public```

# 🔑 AppStoreConnect API
```
GET   |   api/appstoreconnect/get-token
GET   |   api/appstoreconnect/get-full-info
GET   |   api/appstoreconnect/get-app-list
GET   |   api/appstoreconnect/get-app-list/{id}
GET   |   api/appstoreconnect/create-bundle?bundle_id={bundleId}&bundle_name={bundleName}
```

# 🔑 [Jenkins API](https://github.com/jenkinsci/pipeline-stage-view-plugin/tree/master/rest-api)
```
GET   |   api/jenkins/get-job-list
GET   |   api/jenkins/get-job/{projectName}
GET   |   api/jenkins/get-build-list/{projectName}
GET   |   api/jenkins/get-latest-build-info/{projectName}
GET   |   api/jenkins/stop-job/{projectName}/{buildNumber}
```

# 🔑 GitHub API
```
GET   |   api/github/get-repositories
GET   |   api/github/get-repository/{id}
```

# 🔑 Apps API
```
GET   |   api/get-app/{id}
```


# 🔑 Packages API
```
GET   |   api/get-package/{id}
POST  |   api/update-package/{id}/{hash}
```
