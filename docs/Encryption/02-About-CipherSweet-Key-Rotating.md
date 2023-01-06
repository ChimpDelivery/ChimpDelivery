# Rotating Keys
- Should you suspect that somebody got a hold of your encrypting key? 

# Solution
- Copy the output of ```php artisan ciphersweet:generate-key``` and run the following commands
  - ```php artisan ciphersweet:encrypt "App\Models\AppleSetting" <your-new-key>```
  - ```php artisan ciphersweet:encrypt "App\Models\AppStoreConnectSetting" <your-new-key>```
  - ```php artisan ciphersweet:encrypt "App\Models\GithubSetting" <your-new-key>```
  - ```php artisan ciphersweet:encrypt "App\Models\WorkspaceInviteCode" <your-new-key>```
- This will update all the encrypted fields and blind indexes of the models. 
  - Once this is done, you can update your .env or config file to use the new key.
