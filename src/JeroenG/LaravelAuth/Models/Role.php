<?php namespace JeroenG\LaravelAuth\Models;

class Role extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	public function permissions()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Permission');
	}

	public function users()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\User');
	}
}