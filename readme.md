Authorize
==============

!docs Authorize

Rommie: Would you like some docs


Authorize is a port of the well known Authority library for Laravel 4. Most of the functions are similar with some
enhancements. This library patches the system Auth class with super powers, and is meant to verify the authentication
as well as the authority of the current system user.


Installation
------------
This package depends on php5.4 advanced closures. php5.3 is not supported nor will be.
to install add the package dependancy to your composer json file:
```
"wishfoundry/authorize": "dev-master"
```
and in your app/config/app.php file replace the auth serevice provider
```php
#'Illuminate\Auth\AuthServiceProvider',
'Wishfoundry\Authorize\AuthorizeServiceProvider',
```


Migrations
---------
An example migration is provide in src/migrations but is not required. Please customize to suit your needs
An matching example trait is provided for you to use in your User models as well. Simply include with
```
class User {
....
use Wishfoundry\Authorize\AuthorizeUserRoleTrait;
}
```
or customize to suit your needs.


Rules
-----

Authorize does not come with and rules loaded by default. Rules can be added dynamically at any time, thus saving
needless calls to the database. The recommended method of adding rules is to set up a route filter:

In a global filter you could setup some basic aliases

```php
App::before(function($request)
{
	Auth::addAlias('Administrate', ['create', 'view', 'modify', 'delete', 'flag', 'unflag']);
	Auth::addAlias('Moderate',     ['view', 'delete', 'flag', 'unflag']);
	Auth::addAlias('AllButView',   ['create', 'modify', 'delete', 'flag', 'unflag']);
});
```

Then you can define your rules in a named filter


```php
Route::filter('admin', function()
{
	if (Auth::guest())
	{
	    Auth::deny('AllButView', 'Post');
	    Auth::deny('AllButView', 'Comment');

	    /**
	     * Rule actions can be any arbitrary string you decide
	     * except for the reserved word all, which is defined internally
	     */
	    Auth::deny('all', 'User');
	}

    // Only make a DB call if user is logged in
	elseif (Auth::user()->hasRole('admin) )
	{
	    Auth::allow('Administrate', 'User');
	    Auth::allow('Administrate', 'Post');
	    Auth::allow('Administrate', 'Comment');
	}
});


Usage is simple and elegant

```php

if(Auth::can('delete', 'User')
{
    $user->delete()
}
...
if(Auth::cannot('view', 'Comment')
{
    return Redirect::to('unauthorized');
}
// Or by aliases
if(Auth::can('Administrate', 'Post') ...
```


for more advanced useage, Closures can be supplied:
```php
Auth::allow('delete', 'Post')->when(function($post){
    return $this->user()->id == $post->user_id;
});
```
Which passed a variable as:
```php
if(Auth::can('delete', 'Post', $post)
{
    $post->delete();
}
```
