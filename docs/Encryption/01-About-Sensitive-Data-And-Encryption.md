# Basics:
- Workspace Invite Codes and other sensitive-data stored as Encrypted in DB.
- Example Scenario: 
  - Workspace Admin(s) creates Invite Codes for their workspaces.
  - User going to register with provided Invite Code.
  - After register request, we have to use db query for find related Workspace with this provided code.

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

### Leakage

- An important consideration in searchable encryption is leakage, which is information an attacker can gain. 
  - Blind indexing leaks that rows have the same value. If you use this for a field like last name, an attacker can use frequency analysis to predict the values. 
  - In an active attack where an attacker can control the input values, they can learn which other values in the database match.

- Here’s a [great article](https://blog.cryptographyengineering.com/2019/02/11/attack-of-the-week-searchable-encryption-and-the-ever-expanding-leakage-function/) on leakage in searchable encryption. Blind indexing has the same leakage as deterministic encryption.

### Related Docs: 
   - https://paragonie.com/blog/2017/05/building-searchable-encrypted-databases-with-php-and-sql
   - https://paragonie.com/blog/2019/01/ciphersweet-searchable-encryption-doesn-t-have-be-bitter
   - https://www.sitepoint.com/how-to-search-on-securely-encrypted-database-fields/
