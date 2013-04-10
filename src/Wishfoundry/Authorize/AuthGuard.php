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
     *
     * License: MIT
     *
     */

#use Wishfoundry\Authorize\AuthInjector;

#use Wishfoundry\Authorize\TestInject;

class AuthGuard extends \Illuminate\Auth\Guard #implements \Illuminate\Auth\UserProviderInterface
{
    /**
     * Create a new authentication guard.
     *
     * @param  Illuminate\Auth\UserProviderInterface  $provider
     * @param  Illuminate\Session\Store  $session
     * @return void
     */
    public function __construct($provider,  $session)
    {
        $this->session = $session;
        $this->provider = $provider;
        #$this->authorize = new Authorize();
        $this->rules = new RuleRepository;
    }

    /**
     * @var RuleRepository Collection of rules
     */
    protected $rules;

    /**
     * @var array List of aliases for groups of actions
     */
    protected $aliases = array();


    /**
     * Determine if current user can access the given action and resource
     *
     * @return boolean
     */
    public function can($action, $resource, $resourceValue = null)
    {

        $self = $this;

        if ( ! is_string($resource)) {
            $resourceValue = $resource;
            $resource = get_class($resourceValue);
        }

        $rules = $this->getRulesFor($action, $resource);


        if (! $rules->isEmpty()) {
            $allowed = array_reduce($rules->all(), function($result, $rule) use ($self, $resourceValue) {
                $result = $result && $rule->isAllowed($self, $resourceValue);
                return $result;
            }, true);
        } else {
            $allowed = false;
        }

        return $allowed;
    }

    /**
     * Determine if current user cannot access the given action and resource
     * Returns negation of can()
     *
     * @return boolean
     */
    public function cannot($action, $resource, $resourceValue = null)
    {
        return ! $this->can($action, $resource, $resourceValue);
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
        return $this->addRule(true, $action, $resource, $condition);
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
        return $this->addRule(false, $action, $resource, $condition);
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
            $condition->bindTo($this);
        if( is_array($resource))
        {
            foreach($resource as $res)
            {
                $rule = new Rule($allow, $action, $res, $condition);
            }
        }
        else
        {
            $rule = new Rule($allow, $action, $resource, $condition);
        }
        $this->rules->add($rule);
        return $rule;
    }

    /**
     * Define new alias for an action
     *
     * @param string $name Name of action
     * @param array  $actions Actions that $name aliases
     * @return RuleAlias
     */
    public function addAlias($name, $actions)
    {
        $alias = new RuleAlias($name, $actions);
        $this->aliases[$name] = $alias;
        return $alias;
    }


    /**
     * Returns all rules relevant to the given rule and resource
     *
     * @return RuleRepository
     */
    public function getRulesFor($action, $resource)
    {
        $aliases = $this->getAliasesForAction($action);
        return $this->rules->getRelevantRules($aliases, $resource);


    }

    /**
     * Returns the current rule set
     *
     * @return RuleRepository
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Returns all actions a given action applies to
     *
     * @return array
     */
    public function getAliasesForAction($action)
    {
        $actions = array($action);

        foreach ($this->aliases as $key => $alias) {
            if ($alias->includes($action)) {
                $actions[] = $key;
            }
        }

        return $actions;
    }

    /**
     * Returns all aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Returns a RuleAlias for a given action name
     *
     * @return RuleAlias|null
     */
    public function getAlias($name)
    {
        return $this->aliases[$name];
    }





}