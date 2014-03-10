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
	 * Retrieve all users, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @param boolean $withTrashed Should soft-deleted entries be included?
	 * @return mixed
	 **/
	public function all($format, $withTrashed)
	{
		switch ($format) {
			case 'object':
				if($withTrashed) return User::withTrashed()->get();
				return User::all();

			case 'array':
				if($withTrashed) return User::withTrashed()->get()->toArray();
				return User::all()->toArray();
			
			case 'json':
				if($withTrashed) return User::withTrashed()->get()->toJson();
				return User::all()->toJson();
		}
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
	 * @param string $username The name of the user.
	 * @param string $password The password of the user.
	 * @param string $email The email of the user.
	 * @return void
	 **/
	public function addUser($username, $password, $email)
	{
		$date = new \DateTime;

		$user = new User;
		$user->username = $username;
		$user->password = \Hash::make($password);
		$user->email = $email;
		$this->created_at = $date;
		$this->updated_at = $date;
		return $user->save();
	}

	/**
	 * Delete a user.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deleteUser($userId, $withForce = false)
	{
		$user = User::withTrashed()->where('id', '=', $userId)->first();
		if($withForce)
		{
			return $user->forceDelete();
		}
		return $user->delete();
	}

	/**
	 * Check if a user already exists.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included?
	 * @return boolean
	 **/
	public function exists($userId, $withTrashed)
	{
		if($withTrashed)
		{
			$count = User::withTrashed()->where('id', '=', $userId)->count();
			if($count == 1) return true;
			return false;
		}
		$count = User::where('id', '=', $userId)->count();
		if($count == 1) return true;
		return false;
	}

	/**
	 * Check if a username already exists.
	 *
	 * @param string $username The name of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function NameExists($username, $withTrashed)
	{
		if($withTrashed)
		{
			$count = User::withTrashed()->where('username', '=', $username)->count();
			if($count == 1) return true;
			return false;
		}
		$count = User::where('username', '=', $username)->count();
		if($count == 1) return true;
		return false;
	}

	/**
	 * Check if a email is already used.
	 *
	 * @param string $email The email of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function emailExists($email, $withTrashed)
	{
		if($withTrashed)
		{
			$count = User::withTrashed()->where('email', '=', $email)->count();
			if($count == 1) return true;
			return false;
		}
		$count = User::where('email', '=', $email)->count();
		if($count == 1) return true;
		return false;
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