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
	 * Get all permissions
	 *
	 * @return object
	 **/
	public function all()
	{
		return Permission::all();
	}

	/**
	 * Find the permission with the given id.
	 *
	 * @param int $id The id of the permission.
	 * @return object
	 **/
	public function find($id)
	{
		return Permission::find($id);
	}

	/**
	 * Create a new permission.
	 *
	 * @param array $input Name and description.
	 * @return void
	 **/
	public function create($input)
	{
		return Permission::create($input);
	}

	/**
	 * Get the id of the permission with the given name.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @return void
	 **/
	public function getPermissionId($permissionName)
	{
		return Permission::select('id')->where('name', $permissionName)->first();
	}
}