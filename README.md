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
- use the config file to list the tables you want to manage in the UI
- create your own controllers by inheriting CrudBaseController. You can then customize views and define your own routes.

### Using the config file

First, you need to publish the config file:
```bash
php artisan vendor:publish --provider="Helori\LaravelCrudui\CruduiServiceProvider" --tag="config"
```

Then, define the database tables you want to manage:
```php
// config/laravel-crudui.php
return [
    'sections' => [
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
Note that the routes can be customized as you need but they must contain the {section} and optionally the {id} parameters in this order.
Also note that the chosen route path must match the one you use in your menu (see below).
```php
// app/Http/routes.php
Route::get('/crud/{section}/items', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getItems'));
Route::get('/crud/{section}/create-item', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getCreateItem'));
Route::post('/crud/{section}/store-item', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postStoreItem'));
Route::get('/crud/{section}/edit-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getEditItem'));
Route::post('/crud/{section}/update-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postUpdateItem'));
Route::get('/crud/{section}/delete-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@getDeleteItem'));
Route::post('/crud/{section}/update-position', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudController@postUpdatePosition'));
Route::get('/ru/{section}/items', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudSingleController@getItems'));
Route::post('/ru/{section}/update-item/{id}', array('uses' => '\Helori\LaravelCrudui\Controllers\CrudSingleController@postUpdateItem'));
```

Create the links to access each table management (for example in a navigation bar):
```html
// e.g. : resources/views/menu.blade.php
@foreach(config('laravel-crudui.sections') as $key => $section)
    <li class="<% (isset($page_name) && $page_name == $section['page_name']) ? ' active' : '' %>">
        <a href="<% $section['route_url'] %>/items"><% $section['menu_title'] %></a>
    </li>
@endforeach
```


### Inheriting CrudBaseController

If your controllers inherits CrudBaseController, create the routes by adding :
```php
$tables = ['medias', 'articles'];
foreach($tables as $table)
{   
    $controller = ucfirst(camel_case($table)).'Controller';
    Route::get('/crudbase/'.$table.'/items', array('uses' => $controller.'@getItems'));
    Route::get('/crudbase/'.$table.'/create-item', array('uses' => $controller.'@getCreateItem'));
    Route::post('/crudbase/'.$table.'/store-item', array('uses' => $controller.'@postStoreItem'));
    Route::get('/crudbase/'.$table.'/edit-item/{id}', array('uses' => $controller.'@getEditItem'));
    Route::post('/crudbase/'.$table.'/update-item/{id}', array('uses' => $controller.'@postUpdateItem'));
    Route::get('/crudbase/'.$table.'/delete-item/{id}', array('uses' => $controller.'@getDeleteItem'));
    Route::post('/crudbase/'.$table.'/update-position', array('uses' => $controller.'@postUpdatePosition'));
}
```
