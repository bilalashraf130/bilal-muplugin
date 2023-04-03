<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class BonusCertificateData extends Eloquent{

	protected $table = 'bonus_certificate_data';
	protected $primaryKey = 'id';
	protected $casts = [
		'linkedin_review' 			 => 'string',
		'course_star_review'		 => 'string',
		'course_feedback'   		 => 'string',
		'internal_feedback'   		 => 'string',
		'user_id		'   		 => 'integer',
		'course_id'			   		 => 'integer',

	];
	protected $fillable = ['linkedin_review', 'course_star_review', 'course_feedback', 'internal_feedback', 'user_id','course_id'];
}
