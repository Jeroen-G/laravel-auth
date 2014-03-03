<?php namespace JeroenG\LaravelAuth;

use Illuminate\Support\ServiceProvider;

class LaravelAuthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('jeroen-g/laravel-auth');

		// Extending the default Auth of Laravel.
		\Auth::extend('eloquent', function()
		{
			// Retrieving the user model.
			$model = $this->app['config']['auth.model'];
			// Creating an Eloquent provider for the Guard class, which requires the app's hashing class and model from above.
        	$provider = new Providers\EloquentUserProvider($this->app['hash'], $model);
        	// This creates the new, extended Guard class, really the core of this package. It requires the app's session storage class.
			return new Guard($provider, \App::make('session.store'));
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// When using 'UserRepository', Laravel automatically uses the EloquentUserRepository
		$this->app->bind('Repositories\UserRepository', 'JeroenG\LaravelAuth\Repositories\EloquentUserRepository');
		// The same for Roles
		$this->app->bind('Repositories\RoleRepository', 'JeroenG\LaravelAuth\Repositories\EloquentRoleRepository');
		// And for Permissions
		$this->app->bind('Repositories\PermissionRepository', 'JeroenG\LaravelAuth\Repositories\EloquentPermissionRepository');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('laravel-auth');
	}

}
