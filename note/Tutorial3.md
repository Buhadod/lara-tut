#  Authentication and Authoirization 


## Create relation
Ref: https://laravel.com/docs/8.x/eloquent-relationships
We will create new model 'Profile' that linked to the 'User' model. The relationship is 1:1. The same works in 1:M.

```
    php artisan make:model Profile -crmsf
```

In the migration file add foreign as follow:
```
Schema::create('profiles', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255);
    $table->string('bio', 500)->nullable();

    //indicate an unsigned integer represent user id
    //it is good to keep it not nullable to ensure any profile must have a user id
    $table->unsignedBigInteger('user_id');
    //add forign key constraint
    $table->foreign('user_id')->references('id')->on('users');

    $table->timestamps();
});

```

In the 'User' model class you need to add a function as relation.
```
class User extends Model
{
    /**
     * Get the phone associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
        // or $this->hasOne(Profile::class,'user_id','id');
    }
}
```
This indicate that the user has only one instance of profile.
Adding this relation allow user model to access the profile.

You can add the same thing in the 'Profile' class as follow:

```
class Profile extends Model
{
    /**
     * Get the phone associated with the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

Adding this relation allow profile model to access the user.

Using a proper naming in the model and the migration file can ensure for you a proper relation. Otherwise,
you have to use this syntax:
```
    $this->belongsTo(User::class, 'foreign_key', 'owner_key');

    or 

    return $this->hasOne(Phone::class, 'foreign_key', 'local_key');     // for 1:1
    return $this->hasMany(Phone::class, 'foreign_key', 'local_key');    // for 1:M
```

There are no much difference between has*** or belongsTo. It is just based on the naming. You can for instance use only hasOne and hasMany without belong to and call it a day.

You can test the relation in the tinker as follow:
```
    php artisan tinker
    
    User::find(1)           //show you the user info for user id = 1
    User::find(1)->profile  //show you the user profile

    Profile::find(1)        //show you the profile (profile_id = 1)
    Profile::find(1)->user  //show you the user info of that profile

```

## Get the authenticated user
You may some time one to only update the profile of the login user itself. you can use `auth::user()->id` to obtain the user id and then followed by `where` to access the profile of the logen in user as follow:
```
    $user_id = auth::user()->id
    $profile = Profile::where('user_id',$user_id)->get();
```
Now, here you will obtain the profile of that spesfic login user and can do any CRUD operations with it.

## Authorization
#ref: https://spatie.be/docs/laravel-permission/v4/installation-laravel
For introducting new roles into the system (admin,customer support ..etc). You need to use the spatie extension

please follow the instruction in thier web page to install it.

### Usage 
Ref: https://spatie.be/docs/laravel-permission/v4/basic-usage/basic-usage

User class need to be updated as follow:
```
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

Then, you can create role like this in your code
```
use Spatie\Permission\Models\Role;

$role = Role::create(['name' => 'admin']);
$role = Role::create(['name' => 'moderator']);
```

a Good place to create roles are from seeder.

### Assigning role
Ref: https://spatie.be/docs/laravel-permission/v4/basic-usage/role-permissions
Role can be assigned to user using this code
```
$user->assignRole('admin');
// a user can have multiple role but becareful
$user->assignRole('admin', 'moderator');

// to remove a role 
$user->removeRole('admin');

// to check if user has a role
$user->hasAnyRole(['admin']);
```

### Role middleware (role with route)
Ref: https://spatie.be/docs/laravel-permission/v4/basic-usage/middleware

Make sure to add the role middleware before doing this in the kernel.php
```
protected $routeMiddleware = [
    // ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
];
```

Then similar to auth middle ware do the follow:
```

Route::group(['middleware' => ['role:admin']], function () {
    // all routes here will reqiure admin role for user
});



```

```
Route::group(['middleware' => ['auth:api','role:admin']], function () {
    Route::get("/test", function(){
        return "text";
    });
});
```

```
Route::get("/test2/{id}","App\Http\Controllers\Test@hello");
public function hello( $id)
    {
        return $id;
    }

```