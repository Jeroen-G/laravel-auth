<?php namespace JeroenG\LaravelAuth;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;

/**
 * This class is the core of the package. Everything is handles through here. It extends the existing Auth class of Laravel.
 *
 * @package LaravelAuth
 * @author 	JeroenG
 * 
 **/
class Guard extends \Illuminate\Auth\Guard {

	/**
	 * Collection of roles of this user
     * @var \JeroenG\LaravelAuth\Repositories\RoleRepository
     */
	protected $roles;

	/**
	 * Collection of permissions of this user
     * @var \JeroenG\laravelAuth\Repositories\RoleRepository
     */
	protected $permissions;

	/**
	 * Collection of the users
     * @var \JeroenG\laravelAuth\Repositories\UserRepository
     */
	protected $users;

	/**
	 * Create a new authentication guard.
	 *
	 * @param  \Illuminate\Auth\UserProviderInterface  $provider
	 * @param  \Illuminate\Session\Store  $session
	 * @return void
	 */
	public function __construct(UserProviderInterface $provider, SessionStore $session)
	{
		$this->session = $session;
		$this->provider = $provider;
		$this->roles = \App::make('Repositories\RoleRepository');
		$this->permissions = \App::make('Repositories\PermissionRepository');
		$this->users = \App::make('Repositories\UserRepository');
	}

	/**
	 * Retrieve all roles, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return mixed
	 **/
	public function allRoles($format = 'array', $withTrashed = false)
	{
		return $this->roles->all($format, $withTrashed);
	}

	/**
	 * Retrieve all permissions, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return mixed
	 **/
	public function allPermissions($format = 'array', $withTrashed = false)
	{
		return $this->permissions->all($format, $withTrashed);
	}

	/**
	 * Retrieve all users, available in different formats.
	 *
	 * @param string $format The preferred format of the output: object, array (default), json.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return mixed
	 **/
	public function allUsers($format = 'array', $withTrashed = false)
	{
		return $this->users->all($format, $withTrashed);
	}


	/**
	 * Find out if the user has the 'Admin' role.
	 *
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @return boolean
	 **/
	public function isAdmin($userId = null)
	{
		if($this->check())
		{
			if(is_null($userId)) $userId = $this->user()->id;
			if($this->users->isAdmin($userId))
			{
				return true;
			}
			return false;
		}
		return false;
	}

	/**
	 * Find out if the user has a certain permission.
	 *
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @param string $permission The permission to look for.
	 * @return boolean
	 **/
	public function can($permission, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->users->hasPermission($userId, $permission))
		{
			return true;
		}
		return false;
	}

	/**
	 * Find out if a role has a certain permission.
	 *
	 * @param string $roleName The name of the role.
	 * @param string $permissionName The permission to look for.
	 * @return boolean
	 **/
	public function roleCan($roleName, $permissionName)
	{
		$permissionId = $this->permissions->getPermissionId($permissionName);
		$roleId = $this->roles->getRoleId($roleName);
		if($this->roles->hasPermission($roleId, $permissionId))
		{
			return true;
		}
		return false;
	}

	/**
	 * Find out if the user has a certain role.
	 *
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @param string $role The role to look for.
	 * @return boolean
	 **/
	public function is($role, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->users->hasRole($userId, $role))
		{
			return true;
		}
		return false;
	}

	/**
	 * Assign a user a new role.
	 *
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @param string $roleName The name of the role.
	 * @return void
	 **/
	public function giveRole($roleName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->is($roleName, $userId)) return;
		$roleId = $this->roles->getRoleId($roleName);
		$this->users->giveRole($userId, $roleId);
	}

	/**
	 * Assign a user a new permission.
	 *
	 * @param string $permissionName The name of the permission.
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @return void
	 **/
	public function givePermission($permissionName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->can($permissionName, $userId)) return;
		$permissionId = $this->permissions->getPermissionId($permissionName);
		$this->users->givePermission($userId, $permissionId);
	}

	/**
	 * Assign a role a new permission.
	 *
	 * @param string $roleName The id of the role.
	 * @param string $permissionName The id of the permission.
	 * @return void
	 **/
	public function giveRolePermission($permissionName, $roleName)
	{
		if($this->roleCan($roleName, $permissionName)) return;
		$permissionId = $this->permissions->getPermissionId($permissionName);
		$roleId = $this->roles->getRoleId($roleName);
		$this->roles->givePermission($roleId, $permissionId);
	}

	/**
	 * Remove a role from a user.
	 *
	 * @param string $roleName The name of the role.
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @return void
	 **/
	public function takeRole($roleName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->is($roleName, $userId))
		{
			$roleId = $this->roles->getRoleId($roleName);
			$this->users->takeRole($userId, $roleId);
		}
	}

	/**
	 * Remove a permission from a user.
	 *
	 * @param string $permissionName The name of the permission.
	 * @param int $userId The id of the user, if null is given the user logged in is used.
	 * @return void
	 **/
	public function takePermission($permissionName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->can($permissionName, $userId))
		{
			$permissionId = $this->permissions->getPermissionId($permissionName);
			$this->users->takePermission($userId, $permissionId);
		}
	}

	/**
	 * Remove a permission from a role.
	 *
	 * @param int $roleName The name of the role.
	 * @param int $permissionName The name of the permission.
	 * @return void
	 **/
	public function takeRolePermission($permissionName, $roleName)
	{
		$permissionId = $this->permissions->getPermissionId($permissionName);
		$roleId = $this->roles->getRoleId($roleName);
		$this->roles->takePermission($roleId, $permissionId);
	}

	/**
	 * Check if a role already exists.
	 *
	 * @param string $roleName The name of the role as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function roleExists($roleName, $withTrashed = false)
	{
		return $this->roles->exists($roleName, $withTrashed);
	}

	/**
	 * Check if a permission already exists.
	 *
	 * @param string $permissionName The name of the permission as it is in the database.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function permissionExists($permissionName, $withTrashed = false)
	{
		return $this->permissions->exists($permissionName, $withTrashed);
	}

	/**
	 * Check if a user already exists.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withTrashed Should soft-deleted entries be included? Default set to false.
	 * @return boolean
	 **/
	public function userExists($userId, $withTrashed = false)
	{
		return $this->users->exists($userId, $withTrashed);
	}

	/**
	 * Add a new role.
	 * 
	 * @param string $roleName The name of the role.
	 * @param text $description The description of the role.
	 * @param int $level Defines the importance of the role. Default is 0.
	 * @return void
	 **/
	public function addRole($roleName, $description, $level = 0)
	{
		if( ! $this->roles->exists($roleName, true))
		{
			return $this->roles->addRole($roleName, $description, $level);
		}
		throw new \InvalidArgumentException("Role doesn't exist or is soft-deleted");
	}

	/**
	 * Add a new permission.
	 * 
	 * @param string $permissionName The name of the permission.
	 * @param text $description The description of the permission.
	 * @return void
	 **/
	public function addPermission($permissionName, $description)
	{
		if( ! $this->permissions->exists($permissionName, true))
		{
			return $this->permissions->addPermission($permissionName, $description);
		}
		throw new \InvalidArgumentException("Permission doesn't exist or is soft-deleted");
	}

	/**
	 * Add a new user.
	 * 
	 * @param string $username The name of the user.
	 * @param string $password The password of the user.
	 * @param string $email The email of the user.
	 * @return void
	 **/
	public function addUser($username, $password, $email)
	{
		if( ! $this->users->nameExists($username, true) and ! $this->users->emailExists($email, true))
		{
			return $this->users->addUser($username, $password, $email);
		}
		throw new \InvalidArgumentException("User doesn't exist, the username/email is already used, or the user is soft-deleted");
	}

	/**
	 * Delete a role.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param string $roleName The name of the role.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deleteRole($roleName, $withForce = false)
	{
		if($this->roles->exists($roleName, true))
		{
			return $this->roles->deleteRole($roleName, $withForce);
		}
		throw new \InvalidArgumentException("Role doesn't exist");
	}

	/**
	 * Delete a permission.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param string $permissionName The name of the permission.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deletePermission($permissionName, $withForce = false)
	{
		if($this->permissions->exists($permissionName, true))
		{
			return $this->permissions->deletePermission($permissionName, $withForce);
		}
		throw new \InvalidArgumentException("Permission doesn't exist");
	}

	/**
	 * Delete a user.
	 * 
	 * When $withForce is set to true, the removal cannot be undone. If set to false it can be undone.
	 *
	 * @param int $userId The id of the user.
	 * @param boolean $withForce Should it be really deleted?
	 * @return void
	 **/
	public function deleteUser($userId, $withForce = false)
	{
		if($this->users->exists($userId, true))
		{
			return $this->users->deleteUser($userId, $withForce);
		}
		throw new \InvalidArgumentException("User doesn't exist");
	}
}