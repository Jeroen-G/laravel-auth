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
	 * Get all roles
	 *
	 * @return object
	 **/
	public function all()
	{
		return Role::all();
	}

	/**
	 * Find the role with the given id.
	 *
	 * @param int $id The id of the role.
	 * @return object
	 **/
	public function find($id)
	{
		return Role::find($id);
	}

	/**
	 * Create a new role.
	 *
	 * @param string $roleName Name of the role.
	 * @param text $description Description of the role (max 255 characters).
	 * @param smallint $level The importance of the role (in comparison to others).
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
		return Role::select('id')->where('name', '=', $roleName)->first();
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
}