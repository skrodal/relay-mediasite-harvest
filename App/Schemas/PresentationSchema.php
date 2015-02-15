<?php namespace Uninett\Schemas;
/**
 * Names for a certain collection.
 * Used to make it easier to do changes of names in mongo, and to avoid typos in code
 */
class PresentationSchema
{
    const COLLECTION_NAME = "presentations";
    const PRESENTATION_ID = "presId";
    const USERNAME = "username";
    const ORG = "org";
    const CREATED = "created_date";
    const DELETED = "deleted_date";
    Const X = "x";
    Const Y = "y";
    const PATH = "path";
    const ENCODING = "encoding";
    const RESOLUTION = "resolution";
    const SIZE = "size_mib";
    const ENCODETIME = "encodetime_s";
    const QUEUETIME = "queuetime_s";
    const DURATION = "duration_s";
    const TRIMMED = "trimmed_s";

    const FILES = "files";
    const HITS = "hits";
    const TITLE = "title";
    const DESCRIPTION = "description";
}
