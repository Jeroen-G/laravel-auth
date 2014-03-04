<?php namespace JeroenG\LaravelAuth\Models;

/**
 * This is the role model, it is used to retrieve role data from the database.
 * 
 * @package LaravelAuth
 * @subpackage Models
 * @author 	JeroenG
 * 
 **/
class Role extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * Access all of the permissions that belong to a role.
	 *
	 * @return void
	 */
	public function permissions()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Permission');
	}

	/**
	 * Access all of the users that belong to a role.
	 *
	 * @return void
	 */
	public function users()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\User');
	}
}