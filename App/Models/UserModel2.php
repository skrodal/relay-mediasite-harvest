<?php namespace Uninett\Models;
use JsonSerializable;
use MongoDate;
use Uninett\Models\Ecampussql\Model;
use Uninett\Schemas\UsersSchema;


/**
 * Class UserModel
 * @package Uninett\Models
 */
class UserModel2 extends Model
{

	public static $table = "users";

	public static $rules = array(
		'username' => array(
			'required',
			'email',
		),
		'username_on_disk' => array(
			'required',
		),
		'email' => array(
			'required',
			'email'
		),
		'name' => array(
			'required'
		),
		'created_date' => array(
			'required'
		),
		'org' => array(
			'required'
		),
		'status' => array(
			'required',
			'integer'
		),
		'affiliation' => array(
			'required',
		),
	);



	public function andMerge($array){
		$this->attributes = array_merge($this->attributes, $array);

		return $this;
	}

	/*	public function variablesToArray(){
			$var = get_object_vars($this);
			foreach($var as &$value){
				if(is_object($value) && method_exists($value,'variablesToArray')){
					$value = $value->variablesToArray();
				}
			}
			return $var;
		}*/
}
