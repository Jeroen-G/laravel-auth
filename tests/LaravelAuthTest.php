<?php namespace JeroenG\LaravelAuth;

/**
 * This is for testing the package
 *
 * @package LaravelAuth
 * @subpackage Tests
 * @author 	JeroenG
 * 
 **/
class LaravelAuthTest extends \Orchestra\Testbench\TestCase
{

	/**
     * Get package providers.
     * 
     * At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @return array
     */
	protected function getPackageProviders()
	{
	    return array(
            'JeroenG\LaravelAuth\LaravelAuthServiceProvider',
        );
	}

	/**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // reset base path to point to our package's src directory
        $app['path.base'] = __DIR__ . '/../src';

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ));
    }

	/**
     * Setup the test environment.
     *
     * @return void
     */
	public function setUp()
	{
		parent::setUp();

		$artisan = $this->app->make('artisan');
		$artisan->call('migrate', array(
            '--database' => 'testbench',
            '--path'     => 'migrations',
        ));

        \DB::table('users')->insert(array(
            'username' => 'jeroen',
            'password' => \Hash::make('secret'),
            'email'    => 'jeroen@example.com',
            'created_at' => '2014-01-01 00:00:00',
            'updated_at' => '2014-01-01 00:00:00',
        ));

        \DB::table('users')->insert(array(
            'username' => 'foobar',
            'password' => \Hash::make('secret'),
            'email'    => 'foobar@example.com',
            'created_at' => '2014-01-01 00:00:00',
            'updated_at' => '2014-01-01 00:00:00',
        ));

        \DB::table('roles')->insert(array(
            'name' => 'Admin',
            'description' => 'One Role To Rule Them All',
            'created_at' => '2014-01-01 00:00:00',
            'updated_at' => '2014-01-01 00:00:00',
        ));

        \DB::table('permissions')->insert(array(
            'name' => 'edit',
            'description' => 'Ability to edit things',
            'created_at' => '2014-01-01 00:00:00',
            'updated_at' => '2014-01-01 00:00:00',
        ));

        \DB::table('role_user')->insert(array(
            'user_id' => 1,
            'role_id' => 1,
        ));

        \DB::table('permission_user')->insert(array(
            'user_id'       => 2,
            'permission_id' => 1,
        ));
	}

    /**
     * This function is used by tests to log in a user.
     *
     * @return void
     */
    public function login($id)
    {
        $repo = new \JeroenG\LaravelAuth\Repositories\EloquentUserRepository;
        $login_user = $repo->find($id);
        \Auth::login($login_user);
    }

    /**
     * Test if first user is an admin.
     *
     * @test
     */
    public function testIsAdmin()
    {
        $this->login(1);
        $output = \Auth::isAdmin(1);
        $this->assertTrue($output);
    }

    /**
     * Test if second user is not an admin.
     *
     * @test
     */
    public function testIsNotAdmin()
    {
        $this->login(2);
        $output = \Auth::isAdmin(2);
        $this->assertFalse($output);
    }

    /**
     * Test if the first user has a permission.
     *
     * @test
     */
    public function testHasPermission()
    {
        $this->login(2);
        $output = \Auth::can('edit');
        $this->assertTrue($output);
    }

    /**
     * Test if the first user has a role.
     *
     * @test
     */
    public function testHasRole()
    {
        $this->login(1);
        $output = \Auth::is('Admin');
        $this->assertTrue($output);
    }

    /**
     * Test if the second user has a permission while being the admin.
     *
     * @test
     */
    public function testAnotherHasPermission()
    {
        $this->login(1);
        $output = \Auth::can('edit', 2);
        $this->assertTrue($output);
    }

    /**
     * Test if all of the roles can be received.
     *
     * @test
     */
    public function testGettingRoles()
    {
        // Array
        $output_array = \Auth::roles('array');
        $this->assertContains('Admin', $output_array[0], "Getting the roles as array failed");

        // Json
        $json = json_encode($output_array);
        $output_json = \Auth::roles('json');
        $this->assertJsonStringEqualsJsonString($json, $output_json, "Getting the roles as json failed");

        // Object
        $output_object = \Auth::roles('object');
        $this->assertContains('Admin', $output_object[0]->name, "Getting the roles as object failed");

        // Text
        $output_text = \Auth::roles('text');
        $this->assertContains('Admin', $output_text, "Getting the roles as text failed");
    }

    /**
     * Test if all of the permissions can be received.
     *
     * @test
     */
    public function testGettingPermissions()
    {
        // Array
        $output_array = \Auth::permissions('array');
        $this->assertContains('edit', $output_array[0], "Getting the permissions as array failed");

        // Json
        $json = json_encode($output_array);
        $output_json = \Auth::permissions('json');
        $this->assertJsonStringEqualsJsonString($json, $output_json, "Getting the permissions as json failed");

        // Object
        $output_object = \Auth::permissions('object');
        $this->assertContains('edit', $output_object[0]->name, "Getting the permissions as object failed");

        // Text
        $output_text = \Auth::permissions('text');
        $this->assertContains('edit', $output_text, "Getting the permissions as text failed");
    }

    /**
     * Test giving the second user a role.
     *
     * @test
     */
    public function testGivingRole()
    {
        \Auth::giveRole('Admin', 2);
        $output = \Auth::is('Admin', 2);
        $this->assertTrue($output);
    }

    /**
     * Test giving the first user a permission.
     *
     * @test
     */
    public function testGivingPermission()
    {
        \Auth::givePermission('edit', 1);
        $output = \Auth::can('edit', 1);
        $this->assertTrue($output);
    }

    /**
     * Test taking the second user a role.
     *
     * @test
     */
    public function testTakingRole()
    {
        \Auth::takeRole('Admin', 2);
        $output = \Auth::is('Admin', 2);
        $this->assertFalse($output);
    }

    /**
     * Test taking the first user a permission.
     *
     * @test
     */
    public function testTakingPermission()
    {
        \Auth::takePermission('edit', 1);
        $output = \Auth::can('edit', 1);
        $this->assertFalse($output);
    }

    /**
     * Test if a role exists
     *
     * @test
     */
    public function testRoleExists()
    {
        $output = \Auth::roleExists('Admin');
        $this->assertTrue($output);
    }

    /**
     * Test if a permission exists
     *
     * @test
     */
    public function testPermissionExists()
    {
        $output = \Auth::permissionExists('edit');
        $this->assertTrue($output);
    }

    /**
     * Test adding a role
     *
     * @test
     */
    public function testAddRole()
    {
        \Auth::addRole('Member', 'Member of the club', 1);
        $output = \Auth::roleExists('Member');
        $this->assertTrue($output);
    }

    /**
     * Test adding a permission
     *
     * @test
     */
    public function testAddPermission()
    {
        \Auth::addPermission('delete', 'Ability to delete items');
        $output = \Auth::permissionExists('delete');
        $this->assertTrue($output);
    }

    /**
     * Test deleting a role
     *
     * @test
     */
    public function testDeleteRole()
    {
        \Auth::addRole('Member', 'Member of the club', 1);

        \Auth::deleteRole('Member', false);
        $output = \Auth::roleExists('Member', false);
        $this->assertFalse($output);

        $output = \Auth::roleExists('Member', true);
        $this->assertTrue($output);

        \Auth::deleteRole('Member', true);
        $output = \Auth::roleExists('Member', true);
        $this->assertFalse($output);
    }

    /**
     * Test deleting a permission
     *
     * @test
     */
    public function testDeletePermission()
    {
        \Auth::addPermission('delete', 'Ability to delete items');
        
        \Auth::deletePermission('delete', false);
        $output = \Auth::permissionExists('delete', false);
        $this->assertFalse($output);

        $output = \Auth::permissionExists('delete', true);
        $this->assertTrue($output);

        \Auth::deletePermission('delete', true);
        $output = \Auth::permissionExists('delete', true);
        $this->assertFalse($output);
    }
}