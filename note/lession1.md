## Requirement
Please install the following software to start up:

- Composer : https://getcomposer.org/
- Node.js : https://nodejs.org/en/
- Wamp : https://www.wampserver.com/en/
- insomania : https://insomnia.rest/download
- git : https://git-scm.com/downloads
- VScode : https://code.visualstudio.com/

## PhP version (latest)
- Change the php version to latest from wamp. You can do it by click on wamp icon from status window bar in the bottom right corner and select php.

- Also, change the php version for envriomental variable (see here: https://docs.oracle.com/en/database/oracle/machine-learning/oml4r/1.5.1/oread/creating-and-modifying-environment-variables-on-windows.html#GUID-DD6F9982-60D5-48F6-8270-A27EC53807D0). You need to add latest php executable to the path and remove any existance one if there is one.

For example,the path for php in wamp64 is : `C:\wamp64\bin\php\php7.4.9`

## Install laravel via composer
Ref : https://laravel.com/docs/8.x/installation#the-laravel-installer

To install laravel via composer run this command:
```
    composer global require laravel/installer

```

## create new project
Please create a laravel project in the `C:/wamp/www/sites` directory or any equivalent `www` directory for other apache servers.

```
    laravel new <project name>

    laravel new blog
```



## connect to a database
- Go to localhost fropm any browser after runnning wamp
- Go to phpMyadmin and create new database (ex. `laravel`)

From your project, got to .env file and edit your database connection string
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

```
Chnage the value of `DB_DATABASE` to the name of the database you created it.

## Add authorization using breeze
Ref: https://laravel.com/docs/8.x/starter-kits#laravel-breeze-installation

Opne command line and navigate to your project directory and run the following:
```
    composer require laravel/breeze --dev
    php artisan breeze:install
    npm install
    npm run dev
    php artisan migrate:fresh
```

## What is Migratre fresh

This command allow to drop and create new tables in the database using the migration files in the directory `/datamase/migration`
```
    php artisan migrate:fresh
```

## How to fix 1071 key was too long issue ?
Go to App/AppServiceProvider.class

```
    //add this in the uses
    use Illuminate\Support\Facades\Schema;


    public function boot()
        {   
            //add this in the boot function
            Schema::defaultStringLength(191);
        }
```

## Login should be ready and accessabile via


## install Laravel passport
https://laravel.com/docs/8.x/passport