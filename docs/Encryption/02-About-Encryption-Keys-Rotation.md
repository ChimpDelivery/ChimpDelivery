# Rotating Encryption Keys

### Problem
- Should you suspect that somebody got a hold of your encryption keys? 

### Analysis
- There are ```2 Encryption Keys``` available in the app. We must be generate new keys and re-encrypt related models with new key.
  - ```APP_KEY``` (used by Laravel to encrypt cookies etc.)
  - ```CIPHERSWEET_KEY``` (used by Dashboard to encrypt sensitive-data)

### Solution
- Run ```php artisan key:generate --force```
  - This will create new ```APP_KEY```
  - Once this is done, ```.env``` file updated automatically.
- Run ```php artisan dashboard:rotate-key```
  - This will create new ```CIPHERSWEET_KEY``` and update all the Encrypted Fields and Blind Indexes of the related models. 
  - Once this is done, ```.env``` file updated automatically.

### Automated Solution
- Encryption Key Rotation must be scheduled.
