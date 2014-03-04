<?php namespace JeroenG\LaravelAuth;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Session\Store as SessionStore;

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

	public function roles($format = 'array')
	{
		switch ($format) {
			case 'object':
				return $this->roles->all();

			case 'array':
				return $this->roles->all()->toArray();
			
			case 'json':
				return $this->roles->all()->toJson();

			case 'text':
				$roles = $this->roles->all();
				$rolesArray = $roles->toArray();
				$string = '';
				$count =  count($rolesArray);
				// These three lines are for setting the pointer to the last element of the array...
				end($rolesArray);
				// Then save the key number of that element.
				$last = max(array_keys($rolesArray));

				//var_dump($last);
				for ($i=0; $i < $count; $i++)
				{
					if ($i == $last)
					{
						$string .= $roles[$i]->name . '.';
					}
					else
					{
						$string .= $roles[$i]->name . ', ';
					}
				}
				return $string;

			default:
				return $this->roles->all();
		}
	}

	public function permissions($format = 'array')
	{
		switch ($format) {
			case 'object':
				return $this->permissions->all();

			case 'array':
				return $this->permissions->all()->toArray();
			
			case 'json':
				return $this->permissions->all()->toJson();

			case 'text':
				$permissions = $this->permissions->all();
				$permissionsArray = $permissions->toArray();
				$string = '';
				$count =  count($permissionsArray);
				// These three lines are for setting the pointer to the last element of the array...
				end($permissionsArray);
				// Then save the key number of that element.
				$last = max(array_keys($permissionsArray));

				//var_dump($last);
				for ($i=0; $i < $count; $i++)
				{
					if ($i == $last)
					{
						$string .= $permissions[$i]->name . '.';
					}
					else
					{
						$string .= $permissions[$i]->name . ', ';
					}
				}
				return $string;

			default:
				return $this->permissions->all();
		}
	}

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
	}

	public function can($permission, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->users->hasPermission($userId, $permission))
		{
			return true;
		}
		return false;
	}

	public function is($role, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->users->hasRole($userId, $role))
		{
			return true;
		}
		return false;
	}

	public function giveRole($roleName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->is($roleName, $userId)) return;
		$roleId = $this->roles->getRoleId($roleName);
		$this->users->giveRole($userId, $roleId);
	}

	public function givePermission($permissionName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->can($permissionName, $userId)) return;
		$permissionId = $this->permissions->getPermissionId($permissionName);
		$this->users->givePermission($userId, $permissionId);
	}

	public function takeRole($roleName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->is($roleName, $userId))
		{
			$roleId = $this->roles->getRoleId($roleName);
			$this->users->takeRole($userId, $roleId);
		}
	}

	public function takePermission($permissionName, $userId = null)
	{
		if(is_null($userId)) $userId = $this->user()->id;
		if($this->is($permissionName, $userId))
		{
			$permissionId = $this->permissions->getPermissionId($permissionName);
			$this->users->takePermission($userId, $permissionId);
		}
	}
}