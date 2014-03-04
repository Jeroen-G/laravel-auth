<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\User;

class EloquentUserRepository implements UserRepository {
	
	public function all()
	{
		return User::all();
	}

	public function find($id)
	{
		return User::find($id);
	}

	public function create($input)
	{
		return User::create($input);
	}

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

	public function can($userId, $permission)
	{
		return $this->hasPermission($userId, $permission);
	}

	public function is($userId, $role)
	{
		return $this->hasRole($userId, $role);
	}

	public function isAdmin($userId)
	{
		return $this->hasRole($userId, 'Admin');
	}

	public function giveRole($userId, $roleId)
	{
		$user = User::find($userId);
		return $user->roles()->attach($roleId);
	}

	public function takeRole($userId, $roleId)
	{
		$user = User::find($userId);
		return $user->roles()->detach($roleId);
	}

	public function givePermission($userId, $permissionId)
	{
		$user = User::find($userId);
		return $user->permissions()->attach($permissionId);
	}

	public function takePermission($userId, $permissionId)
	{
		$user = User::find($userId);
		return $user->permissions()->detach($permissionId);
	}
}