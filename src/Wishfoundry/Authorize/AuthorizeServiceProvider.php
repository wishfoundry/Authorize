<?php namespace Wishfoundry\Authorize;

use \Illuminate\Support\ServiceProvider;

class AuthorizeServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false; #true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        #$this->package('wishfoundry/authorize');
        $this->registerAuthEvents();
    }

	/**
	 * Register the {{full_package}} service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        
		#$this->package('wishfoundry/authorize', __DIR__.'/../../');
        $this->app['auth'] = $this->app->share(function($app)
        {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['auth.loaded'] = true;

            return new AuthManager($app);
        });
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('auth');
    }

    /**
     * Register the events needed for authentication.
     *
     * @return void
     */
    protected function registerAuthEvents()
    {
        $app = $this->app;

        $app->after(function($request, $response) use ($app)
        {
            // If the authentication service has been used, we'll check for any cookies
            // that may be queued by the service. These cookies are all queued until
            // they are attached onto Response objects at the end of the requests.
            if (isset($app['auth.loaded']))
            {
                foreach ($app['auth']->getDrivers() as $driver)
                {
                    foreach ($driver->getQueuedCookies() as $cookie)
                    {
                        $response->headers->setCookie($cookie);
                    }
                }
            }
        });
    }

}