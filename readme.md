# Auth #
#### *Just another authentication package for Laravel.* ####
[![Build Status](https://travis-ci.org/Jeroen-G/laravel-auth.png)](https://travis-ci.org/Jeroen-G/laravel-auth)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Jeroen-G/laravel-auth/badges/quality-score.png?s=b6107724a6d670dbc77178e9283dd46e02b1ab05)](https://scrutinizer-ci.com/g/Jeroen-G/laravel-auth/)
[![Latest Stable Version](https://poser.pugx.org/jeroen-g/laravel-auth/v/stable.png)](https://packagist.org/packages/jeroen-g/laravel-auth)
- - -

## Installation ##

Add this line to your `composer.json`

	"jeroen-g/laravel-auth": "dev-master"

Then update Composer

    composer update

Add the service provider in `app/config/app.php`:

    'JeroenG\LaravelAuth\LaravelAuthServiceProvider',

The last thing to do is to migrate to create the tables for the users, roles and permissions

	php artisan migrate --package="jeroen-g/laravel-auth"

## Usage ##

### Check if the user has a permission ####

```php
if(Auth::can('edit'))
{
	// show a form to edit stuff.
}
```

### Check if the user has a role ####

```php
if(Auth::is('Moderator'))
{
	// Show a form to edit stuff, if the 'Moderator' role has the 'edit' permission.
}
```

### Check if the user is an admin ####

```php
	Auth::isAdmin();
```

You could for example use this to create a filter that protects your backend.

```php
Route::filter('auth.admin', function()
{
	if ( ! Auth::isAdmin()) return Redirect::to('login');
});
```

### Getting all of the available roles/permissions ###

```php
Auth::roles();

Auth::permissions();
```

Both accept one optional parameter, which is the format of the returned results: array, object, text or json. By default an array is returned.