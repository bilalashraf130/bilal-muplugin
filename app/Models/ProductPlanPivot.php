<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductPlanPivot extends Model
{

	protected $table = 'product_plan_pivot';

	protected $primaryKey = 'id';

    protected $fillable = ['product_id', 'plan_id'];

}
