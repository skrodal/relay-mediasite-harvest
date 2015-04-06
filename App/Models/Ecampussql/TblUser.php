<?php namespace Uninett\Models\Ecampussql; 

class TblUser {

	const TABLE_NAME = "tblUser";

	public $attributes = [];

	/*public $id;
	public $userName;
	public $userEmail;
	public $userDisplayName;
	public $createdOn;*/


	public function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

}