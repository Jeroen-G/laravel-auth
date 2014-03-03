<?php namespace JeroenG\LaravelAuth\Repositories;

use JeroenG\LaravelAuth\Models\Role;

class EloquentRoleRepository implements RoleRepository {
	
	public function all()
	{
		return Role::all();
	}

	public function find($id)
	{
		return Role::find($id);
	}

	public function create($input)
	{
		return Role::create($input);
	}
}