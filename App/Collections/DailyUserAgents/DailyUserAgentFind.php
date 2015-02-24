<?php namespace Uninett\Collections\DailyUserAgents;
//This class uses methods inherited from abstract parent
use Uninett\Database\PictorConnection;
use Uninett\Schemas\DailyUserAgentsSchema;

class DailyUserAgentFind
{
	protected $connection;

    public function __construct(PictorConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findData($date)
    {
        $queryString =
            "SELECT * FROM " . DailyUserAgentsSchema::PICTOR_TABLE_NAME .
            " WHERE Dates = '" . $date->format('Y-m-d') . "' ORDER BY Dates DESC" ;

        return $this->connection->query($queryString);
    }
}
