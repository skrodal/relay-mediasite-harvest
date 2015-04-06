<?php namespace Uninett\Models\Ecampussql; 

class TblUser extends Model {

	public static $table = "tblUser";

	public static $rules = array(
		'userId' => array(
			'required',
			'integer',
		),
		'userName' => array(
			'required'
		),
		'userEmail' => array(
			'required',
			'email'
		),
		'userDisplayName' => array(
			'required'
		),
		'createdOn' => array(
			'required'
		),
	);

}