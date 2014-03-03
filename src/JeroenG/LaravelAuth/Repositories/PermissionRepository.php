<?php namespace JeroenG\LaravelAuth\Repositories;

interface PermissionRepository {
	
	public function all();

	public function find($id);

	public function create($input);
}