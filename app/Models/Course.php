<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Course extends Eloquent
{

	protected $table = 'posts';
	protected $appends = ["permalink","featured_image"];
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
	public function getFeaturedImageAttribute($value)
	{

		return get_the_post_thumbnail_url($this->ID);
	}
	public function getPermalinkAttribute($value)
	{

		return get_permalink($this->ID);
	}

	public function getEnrolliesAttribute($value)
	{

		return UserMeta::where("meta_key","course_{$this->ID}_access_from")->count();
	}
	/**
	 * Get a new query builder for the model's table.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function newQuery()
	{
		return parent::newQuery()->where('post_type', "sfwd-courses");
	}


	public function user()
	{

		return $this->belongsTo('App\Models\User', "post_author", "ID");
	}



	public function contents()
	{

		return $this->hasMany('App\Models\PostMeta', "meta_value", "ID")->where("meta_key", "course_id");
	}

	public function meta()
	{

		return $this->hasMany('App\Models\PostMeta', "post_id", "ID");
	}


	public function categories()
	{

		return $this->hasMany('App\Models\TermRelationships', "object_id", "ID");
	}
}
