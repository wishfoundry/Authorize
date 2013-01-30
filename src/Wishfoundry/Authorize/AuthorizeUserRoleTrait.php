<?php
/**
 * __      ___    _    ___                 _          
 * \ \    / (_)__| |_ | __|__ _  _ _ _  __| |_ _ _  _ 
 *  \ \/\/ /| (_-< ' \| _/ _ \ || | ' \/ _` | '_| || |
 *   \_/\_/ |_/__/_||_|_|\___/\_,_|_||_\__,_|_|  \_, |
 *                                               |__/
 *                                                                               
 * Created by : bngreer
 * 
 * 
 */

trait AuthorizeUserRoleTrait
{
    /**
     * Recommend to eager load by default for performance reasons
     *
     * @var array
     */
    #public $includes = array('roles');

    /**
     * Eloquent User<>Role relationship
     * ================================================================
     *
     * @return Role
     */

    public function roles()
    {
        return $this->hasManyAndBelongsTo('Role', 'role_user')->withTimestamps();
    }

    /**
     * Authorize roles and permissions.
     * ================================================================
     *
     * Check for a specific role
     *
     * @param string
     * @return bool
     */
    public function hasRole($key)
    {
        foreach($this->roles as $role)
        {
            if($role->name == $key)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Authorize roles and permissions.
     * ================================================================
     *
     * Check for many roles
     *
     * @param array
     * @return bool
     */
    public function hasAnyRole($keys)
    {
        if( ! is_array($keys))
        {
            $keys = func_get_args();
        }

        foreach($this->roles as $role)
        {
            if(in_array($role->name, $keys))
            {
                return true;
            }
        }

        return false;
    }
    /**
     * Authorize roles and permissions.
     * ================================================================
     *
     * Check for many roles
     *
     * @param string
     * @return void
     */
    public function attachRole($role_name)
    {
        $role = Role::where('name', '=', $role_name)->first();
        if(isset($role))
        {
            $pivot = DB::table('user_roles')->where('user_id', '=', $this->id)->where('role_id', '=', $role->id)-get();
            if(!isset($pivot))
            {
                DB::table('user_roles')->insert(array( 'role_id' => $role->id, 'user_id' => $this->id, ));
            }
        }
    }
}
