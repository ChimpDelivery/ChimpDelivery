### Nginx Configuration

* Add `localhost` file to configuration path
```
/etc/nginx/sites-available/your-conf
```

* Activate `localhost` configuration by creating symlink
```
sudo ln -s /etc/nginx/sites-available/your-conf /etc/nginx/sites-enabled/
```

