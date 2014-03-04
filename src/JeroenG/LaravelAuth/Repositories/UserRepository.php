<?php namespace JeroenG\LaravelAuth\Repositories;

/**
 * This is the interface for the user repository, which handles all requests for user data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @category Interfaces
 * @author 	JeroenG
 * 
 **/
interface UserRepository {
	
	/**
	 * Get all users
	 *
	 * @return object
	 **/
	public function all();

	/**
	 * Find the user with the given id.
	 *
	 * @param int $id The id of the user.
	 * @return object
	 **/
	public function find($id);

	/**
	 * Create a new user.
	 *
	 * @param array $input Things like username, password and email.
	 * @return void
	 **/
	public function create($input);

	/**
	 * Find out if the user has a certain role.
	 *
	 * @param int $userId The id of the user.
	 * @param string $role The role to look for.
	 * @return boolean
	 **/
	public function hasRole($userId, $role);

	/**
	 * Find out if the user has a certain permission.
	 *
	 * @param int $userId The id of the user.
	 * @param string $permission The permission to look for.
	 * @return boolean
	 **/
	public function hasPermission($userId, $permission);

	/**
	 * Find out if the user has the 'Admin' role.
	 *
	 * @param int $userId The id of the user.
	 * @return boolean
	 **/
	public function isAdmin($userId);

	/**
	 * Assign a user a new role.
	 *
	 * @param int $userId The id of the user.
	 * @param int $roleId The id of the role.
	 * @return void
	 **/
	public function giveRole($userId, $roleId);

	/**
	 * Remove a role from a user.
	 *
	 * @param int $userId The id of the user.
	 * @param int $roleId The id of the role.
	 * @return void
	 **/
	public function takeRole($userId, $roleId);

	/**
	 * Assign a user a new permission.
	 *
	 * @param int $userId The id of the user.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function givePermission($userId, $permissionId);

	/**
	 * Remove a permission from a user.
	 *
	 * @param int $userId The id of the user.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function takePermission($userId, $permissionId);
}