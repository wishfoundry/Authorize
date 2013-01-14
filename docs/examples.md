

Adding rules
-----------

Authorize does not have any rules by default and they must be added manually






from a database:

```php
foreach(DB::table('rules')->where_user_id($user->id)->get() as $permission)
{
    if($permission->type == 'allow')
    {
        Auth::allow($permission->action, $permission->resource);
    }
    else
    {
        Auth::deny($permission->action, $permission->resource);
    }
}
```

