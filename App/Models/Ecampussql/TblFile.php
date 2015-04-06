<?php namespace Uninett\Models\Ecampussql; 

class TblFile extends Model {

	public static $table = "tblFile";

	public static $rules = array(
		'filePresentation_presId' => array(
			'required',
			'integer',
		),
		'fileId' => array(
			'required',
			'integer',
		),
		'filePath' => array(
			'required'
		),
		'createdOn' => array(
			'required'
		),
	);

}