<?php namespace JeroenG\LaravelAuth\Models;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * This is the user model, it is used to retrieve user data from the database.
 * 
 * @package LaravelAuth
 * @subpackage Models
 * @author 	JeroenG
 * 
 **/
class User extends \Eloquent implements UserInterface, RemindableInterface{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Access all of the permissions that belong to a user.
	 *
	 * @return void
	 */
	public function permissions()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Permission');
	}

	/**
	 * Access all of the roles that belong to a user.
	 *
	 * @return void
	 */
	public function roles()
	{
		return $this->belongsToMany('JeroenG\LaravelAuth\Models\Role');
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
}