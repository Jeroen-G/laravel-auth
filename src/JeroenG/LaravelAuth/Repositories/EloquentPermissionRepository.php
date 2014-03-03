<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\Permission;

class EloquentPermissionRepository implements PermissionRepository {
	
	public function all()
	{
		return Permission::all();
	}

	public function find($id)
	{
		return Permission::find($id);
	}

	public function create($input)
	{
		return Permission::create($input);
	}

	public function getRolePermissions()
	{
		$perms = null;
		foreach (\Auth::user()->roles as $role)
                {
                    foreach ($role->permissions as $permission)
                    {
                        $perms .= $permission;
                    }
                }
        return $perms;
	}

	public function getPermissionId($permissionName)
	{
		return Permission::select('id')->where('name', $permissionName)->first();
	}
}