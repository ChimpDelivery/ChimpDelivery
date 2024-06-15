### Configuring MariaDB
```bash
sudo service mariadb start
sudo mysql_secure_installation
```

### MariaDB Prompts
- Enter current password for root(enter for none): `Enter`
- Set root password? [Y/n]: `n`
- From there, you can press `Y` and then `Enter` to accept defaults for all subsequent questions.
This will remove some anonymous users and the test database, disable remove root logins,
and load these new rules so that MariaDB immediately implements the changes you have made.

### Creating an Administrative User
```bash
sudo mariadb
```

```sql
GRANT ALL ON *.* TO 'admin'@'localhost' IDENTIFIED BY 'password' WITH GRANT OPTION;
FLUSH PRIVILEGES;
exit
```
