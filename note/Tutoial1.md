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

## Create new laravel project
Please create a laravel project in the `C:/wamp/www/sites` directory or any equivalent `www` directory for other apache servers.

```
laravel new <project name>

laravel new blog
```



## Connect to your database
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
Go to App/Providers/AppServiceProvider.php

```
//add this in the uses
use Illuminate\Support\Facades\Schema;


public function boot()
    {   
        //add this in the boot function
        Schema::defaultStringLength(191);
    }
```

## Access the system
Your system should be ready and accessabile via 
```
http://localhost/sites/<projectname>/public

//exmaple: http://localhost/sites/blog/public
```

## install Laravel passport
REF: https://laravel.com/docs/8.x/passport

Install passport in your project via composer
```
composer require laravel/passport
```
Run these commands then to create tables for passport and install it

```
php artisan migrate:fresh
php artisan passport:install
```

Go to App/Models/User.php and add the following:

```
//in the uses section
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{   
    //add HasApiToken like this
    use HasApiTokens, HasFactory, Notifiable;
}
```

Go to App/Providers/AuthServiceProvider and add these lines:

```
//in the uses function
use Laravel\Passport\Passport;

public function boot()
{
    $this->registerPolicies();
    //inside the boot functon
    if (! $this->app->routesAreCached()) {
        Passport::routes();
    }
}

```

Go to config/auth.php and changes guards to the follow:
```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
        'hash' => false,
    ],
],

```

Run the following commands at the end:
```
php artisan migrate:fresh
   php artisan passport:install
```

## How to solve Personal access client not found. Please create one issue ?


## Add PassportController for api login and register
Run this command to create empty controller, we will name it `PassportController`.
```
    php artisan make:controller API\PassportController
```
The controller will be avalaible on `App\Http\Controllers\API\PassportController`. Access at and add these functions.

```
    
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->only('name','email','password');
        
        $validator = Validator::make($input, [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $input['password'] = bcrypt($request->password);

        $user = User::create($input);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $input = $request->only('email','password');
        
        $validator = Validator::make($input, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        if (!auth()->attempt($input)) {
            return response(['message' => 'Invalid Credentials'],422);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }

    
}

```

Finally, add in the 'routes/api.php'  routes for these functions 
```
Route::post("/login-api","App\Http\Controllers\API\PassportController@login");
Route::post("/register-api","App\Http\Controllers\API\PassportController@register");
```
And run this command, always run it whenver you add new route
```
php artisan route:cache
```