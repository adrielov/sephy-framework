<?php
/**
 * User: Sephy
 * Date: 12/07/2016
 * Time: 11:16
 */

namespace Core\Lib;

use Core\Error;
use Exception;

class Repository
{
	public $model;

	protected function setModel($model){
		$modelName = ucfirst($model);
		$model = implode('\\', ['App', 'Models', ]).'\\'.$modelName;
		if(class_exists($model)){
			return (new $model());
		}else{
			$exception = new Exception();
			Error::log($exception);
			throw new $exception;
		}
	}
}