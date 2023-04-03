<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
class Options extends Eloquent{

	protected $table = 'options';
	protected $primaryKey = 'option_id';
	// protected $fillable = ['product_id', 'course_id'];

	public function getOptionValueAttribute($value)
	{
		if (is_serialized($value)) {

			return unserialize($value);
		}

		return $value;

	}
}
