# BileMo

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/009227705ac24e0e84f8408f4a83412b)](https://app.codacy.com/gh/Nicolasjmcrt/BileMo?utm_source=github.com&utm_medium=referral&utm_content=Nicolasjmcrt/BileMo&utm_campaign=Badge_Grade_Settings)

API project (# 7) for OC training

------------------------------------------------------------------------------------------------------------------------------------------------------------

#### PREREQUISITES

------------------------------------------------------------------------------------------------------------------------------------------------------------
Make sure you have Composer installed on your machine

#### INSTALLATION

------------------------------------------------------------------------------------------------------------------------------------------------------------
**Download or clone**

Download zip files or clone the project repository with github => [see GitHub documentation](https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository).

**Configure environment**

Create an .env.local file at the root of the project and copy this content:

```
# .env.local
DATABASE_URL="mysql://root:root@localhost:3306/bilemo?serverVersion=5.7"
APP_ENV=dev
```

*Content should be edited according to user needs*

**Install the project**

If necessary, install Composer by following the [instructions](https://getcomposer.org/download/)

In your cmd, go to the directory where you want to install the project and install dependencies with composer:

```
$ cd your\directory
$ composer update
```

Dependencies should be installed in your project.

In MacOS, please do the following:

In the doctrine.yaml file present in config / packages, enter the following line as shown below:

unix_socket: /Applications/MAMP/tmp/mysql/mysql.sock

```
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'
        unix_socket: /Applications/MAMP/tmp/mysql/mysql.sock
```

**Create the database**

1 In the terminal, enter the following command to create the database : 

```
$ php bin/console doctrine:database:create
```

2 Create database structure with migrations:

```
$ php bin/console doctrine:migration:migrate
```

For more realistic and current data, replace the Device.php file found in vendor / mbezahanov / faker-provider-collection / src / Faker / Provider by the one found in the "mbezhanov" folder at the root of the project.

3 Then import the data into the tables with this command:

```
$ php bin/console doctrine:fixtures:load --no-interaction
```

4 Generate the SSL Keys with this command :

```
$ php bin/console lexik:jwt:generate-keypair
```

5 Start the Symfony server to launch the application with the command:

```
$ symfony serve
```

6 And finally, enter the following url to use the API documentation : https://localhost:8000/api/doc

## Ready to go
