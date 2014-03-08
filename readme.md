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

To use this function, make sure to give a user the 'Admin' role.

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

Both accept one optional parameter, which is the format of the returned results: array, object or json. By default an array is returned.

### Assigning a role/permission to a user ###

```php
Auth::giveRole('Member', 2);

Auth::givePermission('edit', 2);
```

In both cases the second parameter is the user id. This is optional, if none is passed, the id of the logged in user is used.

### Assiging a permission to a role ###

```php
Auth::giveRolePermission('edit', 'Admin');
```

The first parameter is the permission, the second the role. To find out if a role has a certain permission, you could use the `roleCan()` function.

```php
Auth::roleCan('Admin', 'edit');
```

### Removing a role/permission from a user/role ###

```php
Auth::takeRole('Member', 2);

Auth::takePermission('edit', 2);

Auth::takeRolePermission('edit', 'Admin');
```

### Check if a role/permission/user exists ###

```php
Auth::roleExists('Admin');

Auth::permissionExists('edit');

Auth::userExists(2);
```

All three functions accept a second parameter, `true` or `false`, which determines if the trashed entries will also be used (trashed entries are soft-deleted from the database, see below).

### Adding a new role/permission/user ###

```php
// Name, description, level (any number)
Auth::addRole('Admin', 'One Role To Rule Them All', 10);

// Name, description
Auth::addPermission('edit', 'Ability to edit stuff');

// Username, password, email
Auth::addUser('Jeroen', 'password123', 'jeroen@example.com');
```

### Delete a role/permission/user ###

```php
Auth::deleteRole('Moderator');

Auth::deletePermission('edit', true);

Auth::deleteUser(2);
```

A second parameter is accepted on all three functions. This boolean states if the entry (role/perm/user) should be deleted with force (default set to false). Soft-deleted (so not with force) will be left out of every request to the database (unless otherwise stated). They can however be restored.