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
	 * Retrieve all roles, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @return mixed
	 **/
	public function all($format);

	/**
	 * Create a new role.
	 *
	 * @param string $roleName Name of the role.
	 * @param text $description Description of the role (max 255 characters).
	 * @param int $level The importance of the role (in comparison to others).
	 * @return void
	 **/
	public function addRole($roleName, $description, $level);

	/**
	 * Get the id of the role with the given name.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @return void
	 **/
	public function getRoleId($roleName);

	/**
	 * Check if a role already exists.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function exists($roleName, $withTrashed);

	/**
	 * Find out if a role has a certain permission.
	 *
	 * @param string $roleId The id of the role.
	 * @param string $permissionId The permission's id to look for.
	 * @return boolean
	 **/
	public function hasPermission($roleId, $permissionId);

	/**
	 * Assign a role a new permission.
	 *
	 * @param int $roleId The id of the role.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function givePermission($roleId, $permissionId);

	/**
	 * Remove a permission from a role.
	 *
	 * @param int $roleId The id of the role.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function takePermission($roleId, $permissionId);
}