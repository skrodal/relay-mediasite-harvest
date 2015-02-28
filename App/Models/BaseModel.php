<?php namespace Uninett\Models; 
abstract class BaseModel {

	protected function variablesToArray(){
		$var = get_object_vars($this);
		foreach($var as &$value){
			if(is_object($value) && method_exists($value,'variablesToArray')){
				$value = $value->variablesToArray();
			}
		}
		return $var;
	}
}