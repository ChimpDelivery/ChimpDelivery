### Supervisor Configuration

#### [Horizon Issue](https://dokov.bg/errors-flood-when-restarting-ubuntu-with-laravel-horizon)
* The issue is at the order of shutting down of dependent softwares or simply said Laravel Horizon depends on the Redis server being available, but it gets shut down before Supervisor and respectively Laravel Horizon. Fortunately the fix is pretty simple.

#### Fix
* You should let `systemd` knows that Supervisor depends on the `Redis Service` being available. When you do that the system restart command will first halt Supervisor and then Redis. You have to replace the `supervisor.service` file with provided.

* supervisor.service path: 

```
/etc/systemd/system/multi-user.target.wants/supervisor.service
```

#### Horizon Config
* Add `horizon.conf` file to `/etc/supervisor/conf.d/` directory.

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start horizon
```

