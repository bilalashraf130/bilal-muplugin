<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class CourseReviewsData extends Eloquent{

	protected $table = 'course_products_pivot';
	protected $primaryKey = 'id';
	protected $fillable = ['product_id', 'course_id'];
}
