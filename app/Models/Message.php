<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
  use SoftDeletes;
  
  protected $fillable = ['from', 'to', 'message', 'read', 'thread'];
  
  protected $dates = ['created_at', 'deleted_at'];
  
  
	public function user()
	{
		return $this->belongsToMany('User');
	}
	
  public function createdTimeAgo(){
    return $this->timeAgo($this->created_at);
  }
  
  
  private function timeAgo($date){
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
       if($diff > 1){
         $plural = 's';
       } else {
         $plural = '';
       }
       
			return $diff . " " . $strTime[$i] . $plural . " ago ";
	   }

  }
}
