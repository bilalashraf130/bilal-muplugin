<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class ACTTrackingData extends Eloquent
{

	protected $table = 'act_tracking';
	protected $primaryKey = 'id';
	protected $casts = [
		'event_name' => 'string',
		'user_email' => 'string',
		'first_name' => 'string',
		'last_name' => 'string',
		'message' => 'string',
		'api_error_message' => 'string',
		'status' => 'boolean'
	];
	protected $fillable = ['event_name', 'user_email', 'first_name', 'last_name', 'message', 'status','api_error_message'];

	public function getStatusAttribute($value)
	{

		if($value===1){
			return "Pushed";
		}
		return "Waiting";

	}


}
