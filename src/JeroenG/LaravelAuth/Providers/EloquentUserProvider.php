<?php namespace JeroenG\LaravelAuth\Providers;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Hashing\HasherInterface;
use Illuminate\Auth\UserInterface;

class EloquentUserProvider extends \Illuminate\Auth\EloquentUserProvider implements UserProviderInterface {

    public function __construct(HasherInterface $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }
    
    /**
     * Validate a user against the given credentials.
     *
     * @param  Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials['password'];

        // Is user password valid?
        if(!$this->hasher->check($plain, $user->getAuthPassword())) {
            throw new \Exception('User password is incorrect');
        }
        // Is the user deleted?
        if ($user->deleted_at !== NULL) {
            throw new \Exception('User is deleted');
        }
        return true;
    }
}