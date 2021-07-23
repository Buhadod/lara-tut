# CRUD

## PHP artisan commands
- To create a contoller `php artisan make:controller <controller name>`
- To create a migration file `php artisan make:migration <table name>`
- To create a model `php artisan make:model <model name>`



## Create model
```
php artisan make:model Item -crmsf
```
- cr : resource controller (Your source code)
- m  : migration (i.e Database table)
- s  : seeder   (allow start the factroy, you can skip it)
- f  : factory  (i.e random data generator)

Note: always keep model singular and Captilise (e.g Item, Product ..etc)
Avoid as much as possbile reserved names (e.g Class, Model ..etc)

## CRUD
See the tutorial for further details (
https://dev.to/kingsconsult/how-to-create-a-secure-crud-restful-api-in-laravel-8-and-7-using-laravel-passport-31fh)

## update migration
Ref: https://laravel.com/docs/8.x/migrations#available-column-types
```
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255);
    $table->string('description', 500)->nullable();
    $table->integer('price')->nullable();
    $table->timestamps();
});
```
## Update model
```
class Item extends Model
{
    ....

    protected $fillable = [
        'name',
        'description',
        'price'
    ];
```
## Update Factroy

Ref: https://github.com/fzaninotto/Faker
```
public function definition()
{
    return [
        'name' => $this->faker->name(),
        'description' => $this->faker->text(),
        'price' => $this->faker->numberBetween(1, 100),
    ];
}
```
Also, add seeder start to DatabaseSeeder.php
```
public function run()
{
    \App\Models\User::factory(1)->create(['email'=>'admin@domain.com']); //user with custom email
    \App\Models\User::factory(10)->create();  //10 users with random info from UserFactory
    \App\Models\Item::factory(10)->create();  // <- Create 10 items
}
```

## Update Controller

    
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return response([ 'items' => $items, 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $item = Item::create($input);

        return response(['item' => $item, 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response(['item' => $item, 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $item->update($request->all());

        return response(['item' => $item, 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response(['message' => 'Deleted']);
    }
    }

## Add route
```
Route::resource('items',"App\Http\Controllers\API\ItemController");
```
## Validation rule

    https://laravel.com/docs/8.x/validation#available-validation-rules

## Upload file example

```
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $input =$request->only('profile_picture','name'); 
        

        //different validation for file
        // 'photo' => 'file|mimes:jpeg,bmp,png'
        // 'report' => 'file|mimes:pdf'

       
        $validator = Validator::make($input, [
            'profile_picture' => 'required|image|max:2048|',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }
        
        //Get file path
        $file_path = $input['profile_picture']->path();

        //Get Extension
        $extension = $input['profile_picture']->extension();

        //Create new file name
        $newfilename = uniqid().".". $extension;

        $upload_status = (move_uploaded_file($file_path,$newfilename));

        if($upload_status)
        {
            return "File uploaded sucessfully with name".$newfilename;
            //TODO: save file name and name in the database
        }
        else{
            return "File to upload a file";
        }
       

    }
}
```