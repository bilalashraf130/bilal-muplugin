<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class Term extends Eloquent{

    protected $table = 'terms';
	protected $hidden = [
		"term_group",
	];
	public function objects()
	{

		return $this->hasMany('App\Models\TermRelationships', "term_taxonomy_id", "term_id");
	}


}
