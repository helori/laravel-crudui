# laravel-crudui
UI tools to perform CRUD operations on Eloquent models

## Installation and setup

```bash
composer require helori/laravel-admin
```

Configure your application:
```php
// config/app.php
'providers' => [
    ...
    Helori\LaravelCrudui\CruduiServiceProvider::class,
];
```

Install frontend dependencies:
```bash
bower init
bower install jquery --save
bower install bootstrap --save
bower install angular --save
bower install jquery-ui --save
bower install jqueryui-touch-punch --save
bower install font-awesome --save
bower install tinymce --save
```

Update your gulpfile:
```js
elixir(function(mix)
{
    mix.sass(
        ["../../../vendor/helori/laravel-crudui/src/assets/sass/**/*.scss"],
        "public/css/crudui.css"
    ).copy(
        ["bower_components/font-awesome/fonts"],
        "public/build/fonts"
    ).scripts(
        [
            "bower_components/jquery/dist/jquery.min.js",
            "bower_components/bootstrap/dist/js/bootstrap.min.js",
            "bower_components/angular/angular.min.js",
            "bower_components/jquery-ui/jquery-ui.min.js",
            "bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"
            "bower_components/tinymce/tinymce.min.js"
        ],
        "public/js/crudui.js", "."
    ).styles(
        [
            "bower_components/bootstrap/dist/css/bootstrap.css",
            "bower_components/font-awesome/css/font-awesome.css",
            "bower_components/jquery-ui/themes/base/jquery-ui.min.css",
            "public/css/crudui.css"
        ],
        "public/css/crudui.css", "."
    ).version(
        [
            "public/js/crudui.js",
            "public/css/crudui.css",
        ]
    );
});
```

If not done yet, install gulp and elixir thanks to the built-in's laravel package.json, and then run Gulp
```bash
sudo npm install
gulp
```

## How to use

There are to ways to use this module:
- Use the config file to define the models to be managed.
- Create your own controllers by inheriting CrudBaseController.

### Using the config file

Publish the config file:
```bash
php artisan vendor:publish --provider="Helori\LaravelCrudui\CruduiServiceProvider" --tag="config"
```

Define the models to be managed:
```php
// config/laravel-crudui.php
return [
    'models' => [
        'articles' => [
	    	'model_class' => \App\Article::class,
	    	'page_name' => 'grarticlesoups',
	    	'route_url' => '/crud/articles',

	    	'menu_title' => 'Articles',
	    	'list_title' => 'Articles list',
	    	'edit_title' => 'Edit article',
	    	'add_text' => 'Add article',

	    	'sort_by' => 'id',
	    	'sort_dir' => 'asc',
	    	'sortable' => false,
	    	'limit' => 10,

	    	'fields' => [
	            ['type' => 'text', 'name' => 'title', 'title' => 'Title', 'list' => true, 'edit' => true, 'filter' => true],
	            ['type' => 'alias', 'name' => 'alias', 'src' => 'title', 'use_id' => true, 'list' => false, 'edit' => true, 'filter' => false],
	            ['type' => 'checkbox', 'name' => 'published', 'title' => 'Published', 'list' => false, 'edit' => true, 'filter' => false],
	            ['type' => 'textarea', 'name' => 'content', 'title' => 'COntent', 'list' => false, 'edit' => true, 'filter' => false],
	        ]
	    ],
	    ...
    ],  
];
```
Create the corresponding tables:
```bash
php artisan make:migration create_articles_table
```
```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title');
            $table->text('content');
            $table->boolean('published')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
```
```bash
php artisan migrate
```

Create Eloquent models for your tables:
```bash
php artisan make:model Article.php
```
```php
// app/Article.php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $table = 'articles';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];
}
```

Add this list of generic routes to your routes.php.
Note that the routes can be customized as you need but they must contain the {model} and optionally the {id} parameters in this order.
Also note that chosen paths must match paths used in your menu view (see below).
```php
// app/Http/routes.php
Route::get('/crud/{model}/items', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getItems'));
Route::get('/crud/{model}/create-item', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getCreateItem'));
Route::post('/crud/{model}/store-item', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postStoreItem'));
Route::get('/crud/{model}/edit-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getEditItem'));
Route::post('/crud/{model}/update-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postUpdateItem'));
Route::get('/crud/{model}/delete-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getDeleteItem'));
Route::post('/crud/{model}/update-position', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postUpdatePosition'));
Route::get('/ru/{model}/items', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudSingleController@getItems'));
Route::post('/ru/{model}/update-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudSingleController@postUpdateItem'));
```

Create the links to access each table management (for example in a navigation bar):
```html
// e.g. : resources/views/menu.blade.php
@foreach(config('laravel-crudui.models') as $key => $model)
    <li class="<% (isset($page_name) && $page_name == $model['page_name']) ? ' active' : '' %>">
        <a href="<% $model['route_url'] %>/items"><% $model['menu_title'] %></a>
    </li>
@endforeach
```


### Inheriting CrudBaseController

If your controllers inherits CrudBaseController, create the routes by adding :
```php
$models = ['medias', 'articles'];
foreach($models as $model)
{   
    $controller = ucfirst(camel_case($model)).'Controller';
    Route::get('/crudbase/'.$model.'/items', array('uses' => $controller.'@getItems'));
    Route::get('/crudbase/'.$model.'/create-item', array('uses' => $controller.'@getCreateItem'));
    Route::post('/crudbase/'.$model.'/store-item', array('uses' => $controller.'@postStoreItem'));
    Route::get('/crudbase/'.$model.'/edit-item/{id}', array('uses' => $controller.'@getEditItem'));
    Route::post('/crudbase/'.$model.'/update-item/{id}', array('uses' => $controller.'@postUpdateItem'));
    Route::get('/crudbase/'.$model.'/delete-item/{id}', array('uses' => $controller.'@getDeleteItem'));
    Route::post('/crudbase/'.$model.'/update-position', array('uses' => $controller.'@postUpdatePosition'));
}
```
