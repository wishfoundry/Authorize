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

class AuthGuard extends \Illuminate\Auth\Guard
{
    //
    private $authorize;

    public function __construct()
    {
        parent::__construct();
        $this->authorize = new Authorize();
    }

    /**
     * Determine if current user can access the given action and resource
     *
     * @return boolean
     */
    public function can($action, $resource, $resourceValue = null)
    {
        $this->authorize->can($action, $resource, $resourceValue = null);
    }
    /**
     * Determine if current user cannot access the given action and resource
     * Returns negation of can()
     *
     * @return boolean
     */
    public function cannot($action, $resource, $resourceValue = null)
    {
        $this->authorize->cannot($action, $resource, $resourceValue );
    }
    /**
     * Define privilege for a given action and resource
     *
     * @param string        $action Action for the rule
     * @param mixed         $resource Resource for the rule
     * @param Closure|null  $condition Optional condition for the rule
     * @return Rule
     */
    public function allow($action, $resource, $condition = null)
    {
        $this->authorize->allow($action, $resource, $condition);
    }
    /**
     * Define restriction for a given action and resource
     *
     * @param string        $action Action for the rule
     * @param mixed         $resource Resource for the rule
     * @param Closure|null  $condition Optional condition for the rule
     * @return Rule
     */
    public function deny($action, $resource, $condition = null)
    {
        $this->authorize->deny($action, $resource, $condition)
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
        $this->authorize->addRule($allow, $action, $resource, $condition )
    }
    /**
     * Define new alias for an action
     *
     * @param string $name Name of action
     * @param array  $actions Actions that $name aliases
     * @return RuleAlias
     */
    public function addAlias($name, $actions){
        $this->authorize->addAlias($name, $actions);
    }

    /**
     * Return the Authorize instance for direct adnvanced manipulation
     *
     * @return Authorize
     */
    public function getAuthorizer()
    {
        return $this->authorize;
    }


}