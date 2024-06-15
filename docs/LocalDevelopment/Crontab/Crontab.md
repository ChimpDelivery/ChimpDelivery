### Crontab

- Run `crontab -e`

- Add cron entry
```bash
* * * * * cd /var/www/html/ChimpDelivery && php artisan schedule:run >> /dev/null 2>&1
```
