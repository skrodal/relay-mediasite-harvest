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

    public function findPresentationWithId($presentationId)
    {
       $queryString = "SELECT filePresentation_presId, fileId, filePath, createdOn from dbo.tblFile
        WHERE (filePath LIKE '\\\kastra%.xml' OR filePath LIKE 'https://screencast%.xml')
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
