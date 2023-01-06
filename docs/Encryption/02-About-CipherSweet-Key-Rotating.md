# Rotating Keys
- Should you suspect that somebody got a hold of your encrypting key? 

# Solution
- Run ```php artisan dashboard:rotate-key```
- This will create new key and update all the encrypted fields and blind indexes of the models. 
  - Once this is done, you can update your .env or config file to use the new key.
