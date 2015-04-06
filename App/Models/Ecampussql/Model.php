<?php namespace Uninett\Models\Ecampussql; 
abstract class Model {

	public $attributes = [];

	public function withAttributes($attributes) {
		$this->attributes = $attributes;
		return $this;
	}

}