<?php
/**
 * Created by PhpStorm.
 * User: kim
 * Date: 2/15/15
 * Time: 8:48 AM
 */

namespace Uninett\Collections\Users;


use Uninett\Collections\Collection;
use Uninett\Schemas\UsersSchema;

class UserLoggerTest extends Collection{
    function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);
    }

    public function update()
    {
        $this->Log('Test 123');
    }
}