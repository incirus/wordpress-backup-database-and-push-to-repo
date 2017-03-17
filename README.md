# Wordpress Backup Database and Push to Repo
## What does it do?
- Dump the Wordpress database into a file (http://stackoverflow.com/a/31531996)
- Update GIT repository

## How to use?
- Put the 'backup.php' at the root of your repo. This can be either your template or whole wordpress folder.
- Update the 'backup.php' with the database details

```
$mysqlUserName      = "username";
$mysqlPassword      = "password";
$mysqlHostName      = "localhost";
$DbName             = "databasename";
$backup_name        = "databasename_backup.sql";
```

- The code in 'functions.php' adds an admin menu link under tools. Change the menu URL if needed.
