<?php namespace JeroenG\LaravelAuth\Repositories;

/**
 * This is the interface for the permission repository, which handles all requests for permission data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @category Interfaces
 * @author 	JeroenG
 * 
 **/
interface PermissionRepository {
	
	/**
	 * Get all permissions
	 *
	 * @return object
	 **/
	public function all();

	/**
	 * Find the permission with the given id.
	 *
	 * @param int $id The id of the permission.
	 * @return object
	 **/
	public function find($id);

	/**
	 * Create a new permission.
	 *
	 * @param array $input Name and description.
	 * @return void
	 **/
	public function create($input);

	/**
	 * Get the id of the permission with the given name.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @return void
	 **/
	public function getPermissionId($permissionName);
}