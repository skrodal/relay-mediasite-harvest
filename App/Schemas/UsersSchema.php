<?php namespace Uninett\Schemas;
/**
 * Names for a certain collection.
 * Used to make it easier to do changes of names in mongo, and to avoid typos in code
 */
class UsersSchema
{
    const COLLECTION_NAME = "users";
    const USERNAME = "username";
    const USERNAME_ON_DISK = "username_on_disk";
    const NAME = "displayname";
    const EMAIL = "email";
    const DATE = "date";
    const CREATED = "created_date";
    const STATUS = "status";
    const AFFILIATION = "affiliation";
    const ORG  = "org";
    const STORAGE = "storage";
}
