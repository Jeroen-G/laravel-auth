<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\User;

/**
 * This class is the repository handling all requests for user data.
 *
 * @package LaravelAuth
 * @subpackage Repositories
 * @author 	JeroenG
 * 
 **/
class EloquentUserRepository implements UserRepository {
	
	/**
	 * Get all users
	 *
	 * @return object
	 **/
	public function all()
	{
		return User::all();
	}

	/**
	 * Find the user with the given id.
	 *
	 * @param int $id The id of the user.
	 * @return object
	 **/
	public function find($id)
	{
		return User::find($id);
	}

	/**
	 * Create a new user.
	 *
	 * @param array $input Things like username, password and email.
	 * @return void
	 **/
	public function create($input)
	{
		return User::create($input);
	}

	/**
	 * Find out if the user has a certain role.
	 *
	 * @param int $userId The id of the user.
	 * @param string $role The role to look for.
	 * @return boolean
	 **/
	public function hasRole($userId, $role)
	{
		$roles = User::find($userId)->roles;
		foreach ($roles as $entry)
		{
			if($entry->name == $role)
			{
				return true;
			}
			return false;
		}	
	}

	/**
	 * Find out if the user has a certain permission.
	 *
	 * @param int $userId The id of the user.
	 * @param string $permission The permission to look for.
	 * @return boolean
	 **/
	public function hasPermission($userId, $permission)
	{
		$permissions = User::find($userId)->permissions;
		foreach ($permissions as $entry)
		{
			if($entry->name == $permission)
			{
				return true;
			}
			return false;
		}
	}

	/**
	 * Find out if the user has the 'Admin' role.
	 *
	 * @param int $userId The id of the user.
	 * @return boolean
	 **/
	public function isAdmin($userId)
	{
		return $this->hasRole($userId, 'Admin');
	}

	/**
	 * Assign a user a new role.
	 *
	 * @param int $userId The id of the user.
	 * @param int $roleId The id of the role.
	 * @return void
	 **/
	public function giveRole($userId, $roleId)
	{
		$user = User::find($userId);
		return $user->roles()->attach($roleId);
	}

	/**
	 * Remove a role from a user.
	 *
	 * @param int $userId The id of the user.
	 * @param int $roleId The id of the role.
	 * @return void
	 **/
	public function takeRole($userId, $roleId)
	{
		$user = User::find($userId);
		return $user->roles()->detach($roleId);
	}

	/**
	 * Assign a user a new permission.
	 *
	 * @param int $userId The id of the user.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function givePermission($userId, $permissionId)
	{
		$user = User::find($userId);
		return $user->permissions()->attach($permissionId);
	}

	/**
	 * Remove a permission from a user.
	 *
	 * @param int $userId The id of the user.
	 * @param int $permissionId The id of the permission.
	 * @return void
	 **/
	public function takePermission($userId, $permissionId)
	{
		$user = User::find($userId);
		return $user->permissions()->detach($permissionId);
	}
}