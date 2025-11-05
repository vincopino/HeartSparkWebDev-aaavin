Copino, Alvin C.
BSIT 3-1

### Database Configuration

On the root folder, import the .sql files

`mysql -u your_username -p your_database_name < init.sql`

`mysql -u your_username -p your_database_name < sample.sql`


Update the database credentials in the configuration file:

> /pages/database/config.php

```
private $host = 'localhost';
private $dbname = 'heart_spark';
private $username = 'root';
private $password = 'password';
private $charset = 'utf8mb4';
```
