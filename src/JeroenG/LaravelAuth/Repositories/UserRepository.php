<?php namespace JeroenG\LaravelAuth\Repositories;

interface UserRepository {
	
	public function all();

	public function find($id);

	public function create($input);

	public function hasRole($userId, $role);

	public function hasPermission($userId, $permission);

	public function isAdmin($userId);
}