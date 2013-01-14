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
    /**
     * Authority constructor
     *
     * @param mixed $currentUser Current user in the application
     */
    public function __construct($currentUser)
    {
        $this->rules = new RuleRepository;
        $this->setCurrentUser($currentUser);
    }

    /**
     * Define rule for a given action and resource
     *
     * @param boolean       $allow True if privilege, false if restriction
     * @param string        $action Action for the rule
     * @param mixed         $resource Resource for the rule
     * @param Closure|null  $condition Optional condition for the rule
     * @return Rule
     */
    public function addRule($allow, $action, $resource, $condition = null)
    {
        if( $condition instanceof Closure)
            $closure->bindTo($this);
        $rule = new Rule($allow, $action, $resource, $condition);
        $this->rules->add($rule);
        return $rule;
    }
}