<?php
/**
 * User: Sephy
 * Date: 04/07/2016
 * Time: 09:12
 */

namespace Core\Lib;

class Request extends \Illuminate\Http\Request
{
	public function redirect($url)
	{
		return header('location: /'.str_replace('.', DS, $url));
	}
}