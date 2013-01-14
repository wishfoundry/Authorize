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



use Authority;

class Authorize extends Authority
{
    public function __construct($user)
    {
        parent::__construct($user);
    }
}