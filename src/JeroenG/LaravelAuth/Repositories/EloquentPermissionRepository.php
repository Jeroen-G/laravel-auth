<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\Permission;

/**
 * This class is the repository handling all requests for permission data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @author JeroenG
 * 
 **/
class EloquentPermissionRepository implements PermissionRepository {
	
	/**
	 * Retrieve all permissions, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @return mixed
	 **/
	public function all($format)
	{
		switch ($format) {
			case 'object':
				return Permission::all();

			case 'array':
				return Permission::all()->toArray();
			
			case 'json':
				return Permission::all()->toJson();
		}
	}
	
	/**
	 * Create a new permission.
	 *
	 * @param string $permissionName Name of the permission.
	 * @param text $description Description of the permission (max 255 characters).
	 * @return void
	 **/
	public function addPermission($permissionName, $description)
	{
		$date = new \DateTime;

		$permission = new Permission;
		$permission->name = $permissionName;
		$permission->description = $description;
		$this->created_at = $date;
		$this->updated_at = $date;
		return $permission->save();
	}

	/**
	 * Delete a permission.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param string $permissionName The name of the permission.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deletePermission($permissionName, $withForce = false)
	{
		$permission = Permission::withTrashed()->where('name', '=', $permissionName)->first();
		if($withForce)
		{
			return $permission->forceDelete();
		}
		return $permission->delete();
	}

	/**
	 * Get the id of the permission with the given name.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @return void
	 **/
	public function getPermissionId($permissionName)
	{
		return Permission::select('id')->where('name', '=', $permissionName)->first()->id;
	}

	/**
	 * Check if a permission already exists.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function exists($permissionName, $withTrashed)
	{
		if($withTrashed)
		{
			$count = Permission::withTrashed()->where('name', '=', $permissionName)->count();
			if($count == 1) return true;
			return false;
		}

		$count = Permission::where('name', '=', $permissionName)->count();
		if($count == 1) return true;
		return false;
	}
}