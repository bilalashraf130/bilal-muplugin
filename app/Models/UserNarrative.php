<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class UserNarrative extends Eloquent{

	protected $table = 'user_narrative';
	protected $primaryKey = 'id';
	protected $casts = [
		'narrative' 		 => 'string',
		'whats_on_your_mind' => 'string',
		'thought_concern'    => 'string',
		'your_hope'          => 'string',
	];
	protected $fillable = ['narrative', 'whats_on_your_mind', 'thought_concern', 'your_hope','user_id'];
	}
