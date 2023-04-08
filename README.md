# About Talus Dashboard
**Talus Dashboard** and its related services are designed to **automate** and **monitor** the **CI/CD Steps** for applications built with the **Unity3D**.
- Provides backend systems such as ```Web Dashboard``` and ```API Endpoints``` that integrated with various platforms such as ```Jenkins```, ```App Store Connect```, ```Google Play```, ```GitHub```.
- Web Dashboard and related services configures ```Jenkins Controller(Master)``` to provide correct workspace and environment settings.
- [Structure](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/CI-CD.drawio) (https://app.diagrams.net/)

## [Example Screenshots](https://github.com/TalusStudio/TalusWebBackend/tree/master/docs/Screenshots)
- [Dashboard - Create App](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/Screenshots/07_dashboard-create-app-1.png)
- [Dashboard - Unity3D Build Log](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/Screenshots/06_dashboard-app-build-log.png) 
- [Dashboard - Create iOS Bundle ID](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/Screenshots/10_dashboard-create-ios-bundle-id.png)
- [Dashboard - Super Admin - Health Checks](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/Screenshots/11_dashboard-superadmin-health.png)
- [Unity3D - Dashboard - Auth](https://github.com/TalusStudio/TalusWebBackend/blob/master/docs/Screenshots_Unity3D/01_Unity3D_Dashboard_Auth.png)
- [Unity3D - Build Layout](https://github.com/TalusStudio/TalusWebBackend/blob/dev/docs/Screenshots_Unity3D/02_Unity3D_Dashboard_Build_Layout.png)
- [Unity3D - Platform Providers](https://github.com/TalusStudio/TalusWebBackend/blob/dev/docs/Screenshots_Unity3D/03_Unity3D_Dasboard_Asset_Data_Providers.png)

## Unity3D Environment
The packages listed below should be added to **Unity3D** projects.
- [TalusBackendData](https://github.com/TalusStudio/TalusBackendData-Package) 
- [TalusCI](https://github.com/TalusStudio/TalusCI-Package)
- [TalusSettings](https://github.com/TalusStudio/TalusSettings-Package)

# :alembic: Environments
- ```.env``` file stored as an ```Environment Secret``` under ```GitHub Actions```.
  - [Production/Staging Server - Configurations](https://github.com/TalusStudio/TalusWebBackend-Deployment)
  - [Jenkins Controller(Master) - Configurations](https://github.com/TalusStudio-Packages/Jenkins-Controller-Docs)
  - [Jenkins Agent(Slave) - Configurations](https://github.com/TalusStudio-Packages/Jenkins-Agent-Docs)

# :label: Pre-Deployment
- [Deployer](https://deployer.org/docs/7.x/recipe/laravel) Configurations
  - [deploy.yaml](https://github.com/TalusStudio/TalusWebBackend/blob/dev/deploy.yaml)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)
- [CipherSweet Key Generation](https://github.com/spatie/laravel-ciphersweet)
- [S3 Bucket Key Generation](https://github.com)
- [Cloudflare Settings](https://dash.cloudflare.com)
- Jenkins Key Generation
  - ```JENKINS_URL/user/USER_NAME/configure -> Api Tokens```
- Database Seeding
  - ```php artisan migrate:fresh --seed```
- Code Quality Check
  - ```composer dashboard-static-analysis``` [[PHPStan](https://phpstan.org)]
- Coding Standards Fixer (CS Fixer)
  - ```composer dashboard-pint```

# :rotating_light: Development Notes
- https://laravelactions.com/
- ```php artisan dashboard:restart-horizon``` 
  - Use this command when you make changes to the code.
- Sync 3rd. Party Library configs(probably inside ```config/``` directory) regularly.

# :tada: Features
- Jenkins API Support
- App Store Connect API Support
- GitHub API Support
- Workspaces & Roles & Permissions
- Daily Backups (S3)
- Dashboard Monitoring as **Super Admin** (LaraLens, Health Checks, Horizon, Telescope, Log Viewer)

# :rocket: Monitoring
- Sentry URL: **https://talusstudio.sentry.io**
- LaraLens: ```{DASHBOARD_URL}/laralens```
- Health Checks: ```{DASHBOARD_URL}/health```
- Horizon: ```{DASHBOARD_URL}/horizon```
- Telescope: ```{DASHBOARD_URL}/telescope```
- Log Viewer: ```{DASHBOARD_URL}/log-viewer```

# :lock: Security
- Honeypot
- ReCaptcha v3
- [Encryption of Sensitive Data](https://github.com/TalusStudio/TalusWebBackend/tree/master/docs/Encryption)
- Pre-Deployment Security Checks with [Enlightn](https://www.laravel-enlightn.com)

# API
- Full details about API Endpoints (inputs, example responses etc.) are in related Postman Workspace.
- Postman Workspace: **https://talusstudio.postman.co**

## 🔑 Apps API
- Optional parameters marked with `?`

```
GET    |   api/get-app?id={id}
POST   |   api/create-app?app_icon={icon?}&app_name={appName}&project_name={projectName}&app_bundle={appBundle}&fb_app_id={fbAppId?}&ga_id={gaID?}&ga_secret={gaSecret?}
POST   |   api/update-app?id={id}&fb_app_id={fbAppID?}&ga_id={gaID?}&ga_secret={gaSecret?}
```

## 🔑 [App Store Connect API](https://developer.apple.com/documentation/appstoreconnectapi)
```
GET    |   api/appstoreconnect/get-token
GET    |   api/appstoreconnect/get-store-apps
GET    |   api/appstoreconnect/get-app-list
GET    |   api/appstoreconnect/get-cert
GET    |   api/appstoreconnect/get-provision-profile
POST   |   api/appstoreconnect/create-bundle?bundle_id={bundleId}&bundle_name={bundleName}
```

## 🔑 [Jenkins API](https://github.com/jenkinsci/pipeline-stage-view-plugin/tree/master/rest-api)
```
GET    |   api/jenkins/get-jobs
GET    |   api/jenkins/get-job?id={id}
GET    |   api/jenkins/get-job-builds?id={id}
GET    |   api/jenkins/get-job-lastbuild?id={id}
GET    |   api/jenkins/get-job-lastbuild-log?id={id}
POST   |   api/jenkins/abort-job?id={id}&build_number={buildNumber}
POST   |   api/jenkins/build-job?id={id}&platform={platform}&storeVersion={storeVersion}
POST   |   api/jenkins/scan-organization
```

## 🔑 [GitHub API](https://docs.github.com/en/rest?apiVersion=2022-11-28)
```
GET    |   api/github/get-repositories
GET    |   api/github/get-repository?project_name={projectName}
GET    |   api/github/get-repository-branches?id={id}
```

# Security Vulnerabilities

If you discover a security vulnerability within project, please send an e-mail to Emre Kovanci via [emrekovanci@talusstudio.com](mailto:emrekovanci@talusstudio.com). All security vulnerabilities will be promptly addressed.

# License

Talus Dashboard is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
