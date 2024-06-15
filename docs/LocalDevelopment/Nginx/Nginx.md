### Nginx Configuration

* Add the `localhost` file to configuration path
```bash
sudo cp ./docs/LocalDevelopment/Nginx/localhost /etc/nginx/sites-available/localhost
```

* Activate the `localhost` configuration by creating symlink
```bash
sudo ln -s /etc/nginx/sites-available/localhost /etc/nginx/sites-enabled/
```

* Remove the `default` configuration symlink in /etc/nginx/sites-enabled/
```bash
sudo rm /etc/nginx/sites-enabled/default
```

* Restart nginx
```bash
sudo service nginx restart
```
