# Talus Dashboard
Provides ```Web Dashboard``` and ```API Endpoints``` that integrated with various platforms such as ```Jenkins```, ```App Store Connect```, ```Google Play```, ```GitHub```.
- [Production Server - Configurations](http://github.com)
- [Jenkins Controller(Master) - Configurations](https://github.com/TalusStudio-Packages/Jenkins-Controller-Docs)
- [Jenkins Agent(Slave) - Configurations](https://github.com/TalusStudio-Packages/Jenkins-Agent-Docs)

# Pre-Deployment
- [Deployer](https://deployer.org/docs/7.x/recipe/laravel) Configurations
  - [deploy.yaml](https://github.com/TalusStudio/TalusWebBackend/blob/dev/deploy.yaml)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)
- [CipherSweet Key Generation](https://github.com/spatie/laravel-ciphersweet)
- [S3 Bucket Key Generation](https://github.com)
- Jenkins Key Generation
  - ```JENKINS_URL/user/USER_NAME/configure -> Api Tokens```
- Code Quality Check
  - ```composer dashboard-static-analysis``` [[PHPStan](https://phpstan.org)]

# Features
- Jenkins API Support
- App Store Connect API Support
- GitHub API Support
- Workspaces & Roles & Permissions
- Daily Backups (S3)
- Dashboard Monitoring as **Super Admin** (Health, Horizon, Telescope)

# Security
- Honeypot
- ReCaptcha v3
- [Encryption of Sensitive Data](https://github.com/TalusStudio/TalusWebBackend/tree/master/docs/Encryption)
- Pre-Deployment Security Checks with [Enlightn](https://www.laravel-enlightn.com)

# 🔑 Apps API
- Optional parameters marked with `?`

```
GET    |   api/get-app?id={id}
POST   |   api/create-app?app_icon={icon?}&app_name={appName}&project_name={projectName}&app_bundle={appBundle}&fb_app_id={fbAppId?}&ga_id={gaID?}&ga_secret={gaSecret?}
POST   |   api/update-app?id={id}&fb_app_id={fbAppID?}&ga_id={gaID?}&ga_secret={gaSecret?}
```

# 🔑 [App Store Connect API](https://developer.apple.com/documentation/appstoreconnectapi)
```
GET    |   api/appstoreconnect/get-token
GET    |   api/appstoreconnect/get-store-apps
GET    |   api/appstoreconnect/get-app-list
GET    |   api/appstoreconnect/get-cert
GET    |   api/appstoreconnect/get-provision-profile
POST   |   api/appstoreconnect/create-bundle?bundle_id={bundleId}&bundle_name={bundleName}
```

# 🔑 [Jenkins API](https://github.com/jenkinsci/pipeline-stage-view-plugin/tree/master/rest-api)
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

# 🔑 GitHub API
```
GET    |   api/github/get-repositories
GET    |   api/github/get-repository?project_name={projectName}
POST   |   api/github/create-repository?project_name={projectName}
```
