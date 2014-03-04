<?php namespace JeroenG\LaravelAuth\Repositories;

/**
 * This is the interface for the role repository, which handles all requests for role data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @category Interfaces
 * @author 	JeroenG
 * 
 **/
interface RoleRepository {
	
	/**
	 * Get all roles
	 *
	 * @return object
	 **/
	public function all();

	/**
	 * Find the role with the given id.
	 *
	 * @param int $id The id of the role.
	 * @return object
	 **/
	public function find($id);

	/**
	 * Create a new role.
	 *
	 * @param array $input Name and description.
	 * @return void
	 **/
	public function create($input);

	/**
	 * Get the id of the role with the given name.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @return void
	 **/
	public function getRoleId($roleName);
}