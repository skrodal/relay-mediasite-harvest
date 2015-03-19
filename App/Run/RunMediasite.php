<?php namespace Uninett\Run; 
use Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed;
use Uninett\Collections\UpdateInterface;

/**
 * Class RunMediasite
 * @package Uninett\Run
 */
class RunMediasite implements RunnableInterface{

	/**
	 * Create and run a new collection of jobs
	 */
	public function run()
	{
		$collections = [
			new MediasiteAggregateSizeUsed

		];
		/* @var $collection UpdateInterface */
		foreach($collections as $collection)
			$collection->update();
	}
}