<?php namespace Uninett\Helpers; 
use DateInterval;
use DatePeriod;
use DateTime;

class StatisticDate {

	private $startDate;
	private $endDate;
	private $dateInterval;
	private $datePeriod;

	/**
	 * If the diff if larger than one day, it means that it has not been imported statistics for 'a while' and storing
	 * the startDate to database would not be correct.
	 * @return mixed
	 */
	public function getDateForDatabase() {
		$days = $this->startDate->diff($this->endDate)->days;

		if($days >= 1)
			return $this->getEndDate();

		return $this->getStartDate();

	}
	/**
	 * @return mixed
	 */
	public function getStartDate()
	{
		return $this->startDate;
	}

	/**
	 * @param mixed $startDate
	 */
	public function setStartDateTodayByTimestamp($timestamp)
	{
		$this->startDate = (new DateTime())->setTimestamp($timestamp);

		return $this;
	}

	public function setStartDateNextDayByTimestamp($timestamp)
	{
		$this->startDate = (new DateTime())->setTimestamp($timestamp);

		$this->startDate->modify('+ 1 day');

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEndDate()
	{
		return $this->endDate;
	}

	/**
	 * @param mixed $endDate
	 */
	public function setEndDateBystring($endDate)
	{
		$this->endDate = new DateTime($endDate);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDateInterval()
	{
		return $this->dateInterval;
	}

	/**
	 * @param mixed $dateInterval
	 */
	public function setDateIntervalFromString($dateInterval)
	{
		$this->dateInterval = DateInterval::createFromDateString($dateInterval);



		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDatePeriod()
	{
		return $this->datePeriod;
	}

	/**
	 * @param mixed $datePeriod
	 */
	public function createDatePeriod()
	{
		$this->datePeriod = new DatePeriod($this->getStartDate(), $this->getDateInterval(), $this->getEndDate());

		return $this;
	}
}