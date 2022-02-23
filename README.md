# Talus Web Backend
- No ssh support for current host(cpanel host), only ftp server.

# :exclamation: Requirements 
- php >= 8.1x on cpanel host (with mbstring extension)

# 💿 Installation (on Local)
- Clone repository and extract it.
- Create ".env" file from ".env.example" template in top project folder.
- Populate ".env" contents.
  - "php artisan key:generate"
  - "php artisan serve"

# 👍 Recommended Development Environment (on Local)
- https://www.apachefriends.org/tr/index.html

# Endpoints
- POST |  api/appinfo?app_name={app_name}&app_bundle={bundle}&fb_app_id={fb_app_id}&elephant_id={elephant_id}&elephant_secret={elephant_secret}
- GET  |  api/appinfo/{db_id}
