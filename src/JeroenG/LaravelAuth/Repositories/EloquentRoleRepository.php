<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\Role;

/**
 * This class is the repository handling all requests for role data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @author 	JeroenG
 * 
 **/
class EloquentRoleRepository implements RoleRepository {
	
	/**
	 * Retrieve all roles, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @return mixed
	 **/
	public function all($format)
	{
		switch ($format) {
			case 'object':
				return Role::all();

			case 'array':
				return Role::all()->toArray();
			
			case 'json':
				return Role::all()->toJson();

			default:
				return Role::all();
		}
	}

	/**
	 * Create a new role.
	 *
	 * @param string $roleName Name of the role.
	 * @param text $description Description of the role (max 255 characters).
	 * @param int $level The importance of the role (in comparison to others).
	 * @return void
	 **/
	public function addRole($roleName, $description, $level)
	{
		$date = new \DateTime;

		$role = new Role;
		$role->name = $roleName;
		$role->description = $description;
		$role->level = $level;
		$this->created_at = $date;
		$this->updated_at = $date;
		return $role->save();
	}

	/**
	 * Delete a role.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param string $roleName The name of the role.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deleteRole($roleName, $withForce = false)
	{
		$role = Role::withTrashed()->where('name', '=', $roleName)->first();
		if($withForce)
		{
			return $role->forceDelete();
		}
		return $role->delete();
	}

	/**
	 * Get the id of the role with the given name.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @return void
	 **/
	public function getRoleId($roleName)
	{
		return Role::select('id')->where('name', '=', $roleName)->first()->id;
	}

	/**
	 * Check if a role already exists.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function exists($roleName, $withTrashed)
	{
		if($withTrashed)
		{
			$count = Role::withTrashed()->where('name', '=', $roleName)->count();
			if($count == 1) return true;
			return false;
		}
		
		$count = Role::where('name', '=', $roleName)->count();
		if($count == 1) return true;
		return false;
	}

	/**
	 * Find out if a role has a certain permission.
	 *
	 * @param int $roleId The id of the role.
	 * @param int $permissionId The permission's id to look for.
	 * @return boolean
	 **/
	public function hasPermission($roleId, $permissionId)
	{
		$permissions = Role::find($roleId)->permissions;
		foreach ($permissions as $entry)
		{
			if($entry->id == $permissionId)
			{
				return true;
			}
			return false;
		}
	}

	/**
	 * Assign a role a new permission.
	 *
	 * @param int $roleId The id of the role.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function givePermission($roleId, $permissionId)
	{
		$role = Role::find($roleId);
		return $role->permissions()->attach($permissionId);
	}

	/**
	 * Remove a permission from a role.
	 *
	 * @param int $roleId The id of the role.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function takePermission($roleId, $permissionId)
	{
		$role = Role::find($roleId);
		return $role->permissions()->detach($permissionId);
	}
}