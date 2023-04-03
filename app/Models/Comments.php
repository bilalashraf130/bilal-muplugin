<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Comments extends Eloquent
{

	protected $table = 'comments';
	protected $primaryKey = 'comment_ID';

	public function meta()
	{

		return $this->hasMany('App\Models\CommentMeta', "comment_id", "comment_ID");
	}

	public function product(){

		return $this->belongsTo('App\Models\Product', 'comment_post_ID','ID');
	}

}
