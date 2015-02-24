<?php namespace Uninett\Run; 
use Uninett\Collections\Mediasite\MediasiteAggregateSizeUsed;
use Uninett\Collections\UpdateInterface;

class RunMediasite implements RunnableInterface{

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