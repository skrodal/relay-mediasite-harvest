<?php namespace Uninett\Collections\Users;
require_once '../../Constants/UserMediasiteNames.php';
require_once '../../Constants/UsersNames.php';
require_once '../../Support/Logging/Logging.php';

class UserCreateTest extends PHPUnit_Framework_TestCase
{
    protected $_user;
    protected $_users;

    public function setUp()
    {
        require_once 'UserCreate.php';
        require_once 'UserModel.php';
        $this->_user = new User();
        $this->_users = new UserCreate();
    }

    public function tearDown()
    {
        $this->_user = null;
        $this->_users = null;
    }

    public function testCreateCorrect()
    {
        $userObj = array
        (
            UserMediasiteSchema::USERNAME => "kim@uninett.no",
            UserMediasiteSchema::CREATED_ON => "asdf",
            UserMediasiteSchema::USERDISPLAYNAME => "Kim Syversen",
            UserMediasiteSchema::USER_EMAIL => "kim@uninett.no",
            UserMediasiteSchema::USER_ID => "123"
        );

        $equals = $this->_users->create($userObj);
        $this->assertNotNull($equals, true);
    }

    public function testCreateWrong1()
    {
        $userObj = array
        (
            UserMediasiteSchema::USERNAME => "kim.@uninett.no",
            UserMediasiteSchema::CREATED_ON => "asdf",
            UserMediasiteSchema::USERDISPLAYNAME => "Kim Syversen",
            UserMediasiteSchema::USER_EMAIL => "kim@uninett.no",
            UserMediasiteSchema::USER_ID => "123"
        );

        $equals = $this->_users->create($userObj);
        $this->assertNull($equals, true);
    }

    public function testCreateWrong2()
    {
        $userObj = array
        (
            UserMediasiteSchema::USERNAME => "kim@uninett.no",
            UserMediasiteSchema::CREATED_ON => "123123",
            UserMediasiteSchema::USERDISPLAYNAME => "Kim Syversen",
            UserMediasiteSchema::USER_EMAIL => "kim.@x.uninett.no",
            UserMediasiteSchema::USER_ID => "123"
        );
        $equals = $this->_users->create($userObj);
        $this->assertNull($equals, true);
    }

    public function testCreateWrong3()
    {
        $userObj = array
        (
            UserMediasiteSchema::USERNAME => "asd.kim.@uninett.no",
            UserMediasiteSchema::CREATED_ON => "123123",
            UserMediasiteSchema::USERDISPLAYNAME => "Kim Syversen",
            UserMediasiteSchema::USER_EMAIL => "kim@uninett.no",
            UserMediasiteSchema::USER_ID => "123"
        );
        $equals = $this->_users->create($userObj);
        $this->assertNull($equals, true);
    }
}
