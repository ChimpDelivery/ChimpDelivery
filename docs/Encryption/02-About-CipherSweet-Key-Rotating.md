# Rotating Encryption Keys

### Problem
- Should you suspect that somebody got a hold of your encryption keys? 

### Solution
- Run ```php artisan key:generate --force```
  - This will create new ```APP_KEY``` used by Laravel to encrypt cookies etc.
  - Once this is done, ```.env``` file updated automatically.
- Run ```php artisan dashboard:rotate-key```
  - This will create new Cipher Sweet Key and update all the Encrypted Fields and Blind Indexes of the models. 
  - Once this is done, ```.env``` file updated automatically.

### Automated Solution
- Encryption Key Rotation must be scheduled
