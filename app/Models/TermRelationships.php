<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class TermRelationships extends Eloquent{

	protected $table = 'term_relationships';

	protected $hidden = [
		"term_order",
	];

	public function term()
	{

		return $this->belongsTo('App\Models\Term', "term_taxonomy_id", "term_id");
	}

}
