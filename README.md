# laravel-crudui
UI tools to perform CRUD operations on Eloquent models

## Installation and setup

```bash
composer require helori/laravel-admin
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
    ],  
];
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