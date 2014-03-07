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
	 * @param string $username The name of the user.
	 * @param string $password The password of the user.
	 * @param string $email The email of the user.
	 * @return void
	 **/
	public function addUser($username, $password, $email);

	/**
	 * Delete a user.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deleteUser($userId, $withForce);

	/**
	 * Check if a user already exists.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function exists($userId, $withTrashed);

	/**
	 * Check if a username already exists.
	 *
	 * @param string $username The name of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function NameExists($username, $withTrashed);

	/**
	 * Check if a email is already used.
	 *
	 * @param string $email The email of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function emailExists($email, $withTrashed);

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