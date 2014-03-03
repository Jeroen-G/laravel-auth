<?php namespace JeroenG\LaravelAuth\Models;

class Permission extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'permissions';

	public function roles()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Role');
	}

	public function users()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\User');
	}
}