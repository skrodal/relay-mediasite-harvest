<?php namespace Uninett\Models;
use MongoDate;

class DailyVideoModel
{
    private $date;
    private $count;

    public function __construct()
    {
        $this->date = new MongoDate();
        $this->count = 0;
    }

    public function setCount($count)
    {
        if(empty($count))
            $count = 0;

        if(!is_int($count))

            return false;

        $this->count = $count;

        return true;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getDate()
    {
        return $this->date;
    }
}
