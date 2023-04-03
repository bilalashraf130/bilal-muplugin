<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Product extends Eloquent
{

	protected $table = 'posts';

	protected $primaryKey = 'ID';
	protected $hidden = [
		"post_author",
		"post_date_gmt",
		"post_status",
		"comment_status",
		"ping_status",
		"post_password",
		"post_name",
		"to_ping",
		"post_modified",
		"post_modified_gmt",
		"post_content_filtered",
		"post_parent",
		"guid",
		"menu_order",
		"post_type",
		"post_mime_type",
		"comment_count",
		"pinged"
	];




	/**
	 * Get a new query builder for the model's table.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function newQuery()
	{
		return parent::newQuery()->where('post_type', "product");
	}
	public function comments(){

		return $this->hasMany('\App\Models\Comments','comment_post_ID','ID');
	}
	public function meta()
	{

		return $this->hasMany('App\Models\PostMeta', "post_id", "ID");
	}

	public function course_products()
	{

		return $this->hasMany('App\Models\CourseReviewsData', "product_id", "ID");
	}
}
