<?php namespace JeroenG\LaravelAuth\Models;

/**
 * This is the permission model, it is used to retrieve permission data from the database.
 * 
 * @package LaravelAuth
 * @subpackage Models
 * @author 	JeroenG
 * 
 **/
class Permission extends \Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'permissions';

	/**
	 * Access all of the roles that belong to a permission.
	 *
	 * @return void
	 */
	public function roles()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Role');
	}

	/**
	 * Access all of the users that belong to a permission.
	 *
	 * @return void
	 */
	public function users()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\User');
	}
}