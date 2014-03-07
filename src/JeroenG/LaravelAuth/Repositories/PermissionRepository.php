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
	 * @param string $permissionName Name of the permission.
	 * @param text $description Description of the permission (max 255 characters).
	 * @param smallint $level The importance of the permission (in comparison to others).
	 * @return void
	 **/
	public function addPermission($permissionName, $description);

	/**
	 * Delete a permission.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param string $permissionName The name of the permission.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deletePermission($permissionName, $withForce = false);

	/**
	 * Get the id of the permission with the given name.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @return void
	 **/
	public function getPermissionId($permissionName);

	/**
	 * Check if a permission already exists.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function exists($permissionName, $withTrashed);
}