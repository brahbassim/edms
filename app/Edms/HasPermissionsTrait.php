<?php

namespace App\Edms;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

trait HasPermissionsTrait {
    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_user');
    }

    /**
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole( ... $roles )
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    /**
     * @param $permission
     * @return bool
     */
    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }
}
