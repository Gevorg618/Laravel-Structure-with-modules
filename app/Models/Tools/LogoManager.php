<?php

namespace App\Models\Tools;

use App\Models\BaseModel;

class LogoManager extends BaseModel
{
	/**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'logos';

	protected $fillable = [
		'title',
		'image',
		'is_active',
		'start_date',
		'end_date',
	];
}
