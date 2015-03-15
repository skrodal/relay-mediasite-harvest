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