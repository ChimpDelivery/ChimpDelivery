# 🌐 Talus Dashboard
[![deploy-staging](https://github.com/TalusStudio/TalusWebBackend/actions/workflows/deploy-staging.yml/badge.svg?branch=dev)](https://github.com/TalusStudio/TalusWebBackend/actions/workflows/deploy-staging.yml)
[![deploy-prod](https://github.com/TalusStudio/TalusWebBackend/actions/workflows/deploy-prod.yml/badge.svg)](https://github.com/TalusStudio/TalusWebBackend/actions/workflows/deploy-prod.yml)
- Provides Web Dashboard and Backend APIs to work with ***Unity3D CI/CD Pipeline***.
- [Build Mac - Environment Setup](https://github.com/TalusStudio-Packages/Build-Mac-Environment)
- [Build Mac - Jenkins Setup](https://github.com/TalusStudio-Packages/Jenkins-Docs)
- [Google Captcha Key Generation](https://www.google.com/recaptcha/admin/create)

# Information
- Deployment configurations: [deploy.yaml](https://github.com/TalusStudio/TalusWebBackend/blob/dev/deploy.yaml)
- App Store Connect API Support
- Test Flight API Support
- GitHub API Support


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
GET    |   api/appstoreconnect/get-full-info
GET    |   api/appstoreconnect/get-app-list
GET    |   api/appstoreconnect/get-build-list
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

# 🔑 Packages API
```
GET   |   api/get-package?package_id={id}
GET   |   api/get-packages
POST  |   api/update-package?package_id={id}&hash={hash}
```
