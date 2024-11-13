<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalaryLevel
 * 
 * @property int $id
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SalaryLevel extends Model
{
	protected $table = 'salary_level';

	protected $casts = [
		'level' => 'int'
	];

	// protected $fillable = [
	// 	'level'
	// ];
	protected $guarded = ['id'];

	public function users()
	{
		return $this->hasMany(User::class);
	}
}
