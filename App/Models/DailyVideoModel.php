<?php namespace Uninett\Models;
use MongoDate;

/**
 * Class DailyVideoModel
 * @package Uninett\Models
 */
class DailyVideoModel
{

	/**
	 * @var MongoDate
	 */
	private $date;
	/**
	 * @var int
	 */
	private $count;

	/**
	 *
	 */
	public function __construct()
    {
        $this->date = new MongoDate();
        $this->count = 0;
    }

	/**
	 * @param $count
	 * @return bool
	 */
	public function setCount($count)
    {
        if(empty($count))
            $count = 0;

        if(!is_int($count))

            return false;

        $this->count = $count;

        return true;
    }

	/**
	 * @return int
	 */
	public function getCount()
    {
        return $this->count;
    }

	/**
	 * @return MongoDate
	 */
	public function getDate()
    {
        return $this->date;
    }
}
