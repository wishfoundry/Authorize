<?php namespace Wishfoundry\Authorize;
/**
 * __      ___    _    ___                 _          
 * \ \    / (_)__| |_ | __|__ _  _ _ _  __| |_ _ _  _ 
 *  \ \/\/ /| (_-< ' \| _/ _ \ || | ' \/ _` | '_| || |
 *   \_/\_/ |_/__/_||_|_|\___/\_,_|_||_\__,_|_|  \_, |
 *                                               |__/
 *                                                                               
 * Created by : bngreer
 * Date: 1/10/13    
 * Copyright 2013 The WishFoundry / Ben Greer. All Rights Reserved.
 * 
 * 
 */


class AuthManager extends \Illuminate\Auth\AuthManager
{

    /**
     * Create an instance of the Eloquent driver.
     *
     * @return Illuminate\Auth\Guard
     */
    public function createEloquentDriver()
    {
        $provider = $this->createEloquentProvider();

        return new AuthGuard($provider, $this->app['session']);
    }

    /**
     * Create an instance of the database driver.
     *
     * @return Illuminate\Auth\Guard
     */
    protected function createDatabaseDriver()
    {
        $provider = $this->createDatabaseProvider();

        return new AuthGuard($provider, $this->app['session']);
    }
}