<?php namespace Uninett\Collections\Users;
//Creates an User object with by using the result from UserFind

use Uninett\Collections\Collection;
use Uninett\Models\UserModel2;
use Uninett\Models\UserModel;
use Uninett\Schemas\UserRelaySchema;
use Uninett\Schemas\UsersSchema;

class UserCreate extends Collection
{
    const StatusNotSet = -1;

    function __construct()
    {
        parent::__construct(UsersSchema::COLLECTION_NAME);
    }

	/**
	 * Create new user
	 * @param $res
	 * @return null|UserModel2
	 */
    public function create($res)
    {
        $user = new UserModel;

        $usernameWasAnEmail = $user->setUsername($res[UserRelaySchema::USERNAME]);

        if ($usernameWasAnEmail) {
            $user->setCreatedDate($res[UserRelaySchema::CREATED_ON]);

            $user->setName($res[UserRelaySchema::USERDISPLAYNAME]);

            $emailValid = $user->setEmail($res[UserRelaySchema::USER_EMAIL]);

            if (!$emailValid) {
                $this->LogError("Could not add " .
                    $res[UserRelaySchema::USERNAME] . ". because userEmail did not look like an email. Ignored");

                return null;
            }

            $userNameOnDisk = str_replace('@', '', $res[UserRelaySchema::USERNAME]);

            $user->setUsernameOnDisk($userNameOnDisk);

            $org = explode('@', $res[UserRelaySchema::USERNAME]);

            if(isset($org[1]))
                $user->setOrg($org[1]);

            $user->setStatus(UserCreate::StatusNotSet);

            $user->setAffiliation("willBeSetIfFolderExistsAndUserSetAffiliationHaveDoneItsThing");
        } else {
            $this->LogError("Found user: " . $res[UserRelaySchema::USERNAME]  . ", but could not create it" . ". Ignored");

            return null;
        }

        return $user;
    }
}
