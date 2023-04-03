<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class PostMeta extends Eloquent{

	protected $table = 'postmeta';
	protected $primaryKey = 'post_id';


	public function getMetaValueAttribute($value)
	{
		if (is_serialized($value)) {

			return unserialize($value);
		}

		return $value;

	}

	public function getLdCourseStepsAttribute(){

		return 123;
	}



	public function getMetaKeyAttribute($value)
	{
		if($value==="ld_course_steps"){

			$this->appends = ["ld_course_steps"];
		}


		return $value;

	}


	public function post()
	{

		return $this->belongsTo('App\Models\Post', "post_id", "ID");
	}
}
