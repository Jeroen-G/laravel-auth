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
	 * @param array $input Name and description.
	 * @return void
	 **/
	public function create($input)
	{
		return Role::create($input);
	}

	/**
	 * Get the id of the role with the given name.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @return void
	 **/
	public function getRoleId($roleName)
	{
		return Role::select('id')->where('name', $roleName)->first();
	}
}