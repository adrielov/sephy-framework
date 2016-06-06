<?php
/**
 * User: Sephy
 * Date: 06/06/2016
 * Time: 04:30
 */

namespace Core\Database;
use Illuminate\Database\Eloquent\Model as EloquetModel;

class Model extends EloquetModel {

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;

}