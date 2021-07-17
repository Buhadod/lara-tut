## Req
composer, node.js, wamp

## Install laravel via composer
Ref : https://laravel.com/docs/8.x/installation#the-laravel-installer
composer global require laravel/installer
laravel new example-app

## Add auth using breeze
Ref: https://laravel.com/docs/8.x/starter-kits#laravel-breeze-installation
composer require laravel/breeze --dev

php artisan breeze:install

npm install
npm run dev
php artisan migrate


## Migrate fresh 
php artisan migrate:fresh


## fix 1071 key was too long issue ?
Go to AppServiceProvider

```
use Illuminate\Support\Facades\Schema;
public function boot()
    {
        Schema::defaultStringLength(191);
    }
```

## install Laravel passport
https://laravel.com/docs/8.x/passport