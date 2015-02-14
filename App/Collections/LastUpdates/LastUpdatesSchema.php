<?php namespace Uninett\Collections\LastUpdates;
/**
 * Names for a certain collection.
 * Used to make it easier to do changes of names in mongo, and to avoid typos in code
 */
class LastUpdatesSchema
{
    const COLLECTION_NAME = "lastupdates";
    const DOCUMENT_KEY = "documentkey";
    const ID = "_id";
    const USER_ID = "userId";
    const PRESENTATION_ID = "presentationId";
}
