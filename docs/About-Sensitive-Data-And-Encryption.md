# Basics:
- Workspace Invite Codes and other sensitive-data stored as Encrypted in DB.
- Usage: Workspace Admin(s) creates Invite Codes for their workspaces.

### Problem in DB Queries: There is no functionality like that!
1. Encrypt User Input
2. Search Encrypted Column with Encrypted Input
3. Actions
4. Response

### Analysis
- Encryption produce different hashes with same input for security related topics.

### Bad Solution
- Get all Invite Codes and decrypt by-one-by and compare.

### Better Solution
- Blind Indexes (in CipherSweet by Spatie)

### Related Docs: 
   - https://paragonie.com/blog/2017/05/building-searchable-encrypted-databases-with-php-and-sql
   - https://paragonie.com/blog/2019/01/ciphersweet-searchable-encryption-doesn-t-have-be-bitter
   - https://www.sitepoint.com/how-to-search-on-securely-encrypted-database-fields/
