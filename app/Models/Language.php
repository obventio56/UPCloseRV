<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  
    protected $table = 'language';
	
	public function scopeGetArray($q)
	{
		$results = $q->get();
		$new = array();
		foreach($results as $row){
			$new[$row->name] = $row->value;
		}
		
		return $new;
	}
}