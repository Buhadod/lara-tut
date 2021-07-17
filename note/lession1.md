## Req
- Composer : https://getcomposer.org/
- Node.js : https://nodejs.org/en/
- Wamp : https://www.wampserver.com/en/
- insomania : https://insomnia.rest/download
- git : https://git-scm.com/downloads
- VScode : https://code.visualstudio.com/


## Install laravel via composer
Ref : https://laravel.com/docs/8.x/installation#the-laravel-installer

To install laravel via composer run this command:
```
    composer global require laravel/installer

```

To create new project
```
    laravel new <project name>

    laravel new blog
```

## connect to a database
Go to localhost fropm any browser after runnning wamp
Go to phpMyadmin and create new database

From your project, got to .env file and edit your database connection string
```


``


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

## Who tp fix 1071 key was too long issue ?
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

## Login shoulr ready and accessabile via

## install Laravel passport
https://laravel.com/docs/8.x/passport