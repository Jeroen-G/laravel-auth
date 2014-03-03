<?php namespace JeroenG\LaravelAuth\Repositories;

interface RoleRepository {
	
	public function all();

	public function find($id);

	public function create($input);
}