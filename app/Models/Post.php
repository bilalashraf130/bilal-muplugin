<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class Post extends Eloquent{
	protected $primaryKey = 'ID';
	protected $table = 'posts';
	protected $hidden = [
		"post_author",
		"post_date",
		"post_date_gmt",
		"post_excerpt",
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
		"post_mime_type",
		"comment_count",
		"pinged"
	];
	public function meta()
	{

		return $this->hasMany('App\Models\PostMeta', "post_id", "ID");
	}
}
