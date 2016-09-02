<?php namespace Uninett\Collections\Presentations;
//This class is used to find presentations in source database
use Uninett\Database\RelaySQLConnection;

class PresentationFind
{
	private $connection;

    function __construct(RelaySQLConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Some notes on Relay's tblFile:
     *
     * - A presentation will have 30+ entries in tblFile listing all files involved (log, js, swf, mp3, mp4, xml, css, html...),
     * - Involves paths on local (c:) and publised (screencast/kastra/samba)
     * - In the query, ignore local files - what we're after are published XML-files only.
     * - Thus, the function will typically return 4 of 30+ rows for a single presentation.
     *
     * @param $presentationId
     *
*@return bool|mixed
     */
    public function findXMLsForPresentationWithId($presentationId)
    {
       $queryString = "SELECT filePresentation_presId, fileId, filePath, createdOn from tblFile
        /* WHERE (filePath LIKE '\\\kastra%.xml' OR filePath LIKE 'https://screencast%.xml' OR filePath LIKE '\\\\samba0%.xml') */
        WHERE filePath LIKE '%.xml'
        AND filePath NOT LIKE 'C:\\%'
        AND filePath NOT LIKE '%video_xmp.xml'
        AND filePresentation_presId LIKE '" . $presentationId  .  "' ORDER BY fileId ASC";

        return $this->connection->query($queryString);
    }

    public function findHighestPresentationsId()
    {
        $queryString = "SELECT MAX(filePresentation_presID) FROM tblFile";

        return $this->connection->query($queryString);
    }
}
