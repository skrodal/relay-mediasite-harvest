<?php namespace Uninett\Collections\Users;
//Creates an User object with by using the result from UserFind
use Carbon\Carbon;
use Monolog\Logger;

class UserCreate
{
    const StatusNotSet = -1;

    private $_log;

    function __construct()
    {
        $this->_log = new Logger('import');
    }

    public function create($res)
    {
        $user = new User();

        $usernameWasAnEmail = $user->setUsername($res[UserMediasiteSchema::USERNAME]);

        if ($usernameWasAnEmail) {
            $user->setDate($res[UserMediasiteSchema::CREATED_ON]);

            $user->setName($res[UserMediasiteSchema::USERDISPLAYNAME]);

            $emailValid = $user->setEmail($res[UserMediasiteSchema::USER_EMAIL]);

            if (!$emailValid) {
                $this->_log->addError("Could not add " .
                    $res[UserMediasiteSchema::USERNAME] . ". because userEmail did not look like an email. Ignored");

                return null;
            }

            $userNameOnDisk = str_replace('@', '', $res[UserMediasiteSchema::USERNAME]);

            $user->setUsernameOnDisk($userNameOnDisk);

            $org = explode('@', $res[UserMediasiteSchema::USERNAME]);

            if(isset($org[1]))
                $user->setOrg($org[1]);

            $user->setStatus(UserCreate::StatusNotSet);

            $user->setAffiliation("willBeSetIfFolderExistsAndUserSetAffiliationHaveDoneItsThing");
        } else {
            $this->_log->addWarning("Found user: " . $res[UserMediasiteSchema::USERNAME]  . ", but could not create it" . ". Ignored");

            return null;
        }

        return $user;
    }
}
