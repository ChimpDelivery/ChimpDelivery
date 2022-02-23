# Talus Web Backend
- No ssh support for current host(cPanel host), only ftp server.

# :exclamation: Requirements on cPanel
- php >= 8.1x on cpanel host (with mbstring extension)
- laravel >= 9.1

# 💿 Installation (on Local)
- Install XAMPP from https://www.apachefriends.org/tr/index.html and install it.
- Open XAMPP control panel and start Apache and MySQL modules.
- Clone repository and extract it.
- Create ".env" file from ".env.example" template in project folder.
- Populate ".env" contents.
- Open bash/powershell etc. in project folder.

```
php artisan key:generate
php artisan migrate
php artisan serve
```

- Recommended REST client for testing API https://www.postman.com 

# 🔑 Endpoints
```
GET  |  api/appinfo/{db_id}
```
