<?php

namespace Core;

use Symfony\Component\HttpFoundation\Response;

class Error
{
	/**
	 * Determines how the error log will be based on the environment variable setada the settings.
	 */
	public static function log($ex)
	{
		$env = Config::get('app.enviroment');

		if ($env == 'development') {
			self::logDev($ex);
		} else {
			self::logProduction($ex);
		}
		die();
	}

	/**
	 * Logs errors in file while directs the user to a generic error page.
	 *
	 * @param \Exception $ex
	 */
	public static function logProduction(\Exception $ex)
	{
		$message 	= $ex->getMessage();
		$code 		= $ex->getCode();
		$file 		= $ex->getFile();
		$line 		= $ex->getLine();
		$trace 		= $ex->getTraceAsString();

		$log_folder = ROOT.DS.'errors'.DS;

		$log = fopen($log_folder.'ERROS '.date('d-m-Y').'.log', 'a+');

		fwrite($log, 'message: '.$message.PHP_EOL);
		fwrite($log, 'code: '.$code.PHP_EOL);
		fwrite($log, 'file: '.$file.PHP_EOL);
		fwrite($log, 'line: '.$line.PHP_EOL);
		fwrite($log, 'trace: '.$trace.PHP_EOL);
		fwrite($log, '================================================================================'.PHP_EOL);
		fclose($log);
		
	}

	/**
	 * Sends the data to the screen for review by the developer.
	 *
	 * @param \Exception $ex
	 */
	public static function logDev(\Exception $ex)
	{
		self::uncaughtExceptionHandler($ex);
	}

	/**
	 * Logs errors in file while directs the user to a generic error page.
	 */
	public static function logFile($text)
	{
		$log_folder = ROOT.DS.'errors'.DS;
		$log = fopen($log_folder.'ERROS '.date('d-m-Y').'.log', 'a+');

		fwrite($log, $text.PHP_EOL);
		fwrite($log, '================================================================================'.PHP_EOL);
		fclose($log);
	}

	public static function uncaughtExceptionHandler($e)
	{
		if(Config::get('app.debug'))
			$trace = str_replace('#', '<hr>', $e->getTraceAsString());
		else
			$trace = '<hr> <a href="/" class="btn btn-success ">Go to Home</a>';
		$title = Config::get("app.title");
		$html = /**@lang text */
			"<html>
				<head>
					<title>".$title." :: ERROR </title>
					<link href=\"/assets/css/core-default.css\" rel=\"stylesheet\">
					<meta charset='utf-8' content='text/html' />
				</head>
				<body style=\"background: #f44336;\">
				<div class=\"content\">
					<div id=\"wrapper\">
						<!-- Error title -->
						<div class=\"content-group\">				
							<h1 class=\"error-title\">Ops!</h1>
							<h5>{$e->getMessage()}</h5>
							<div style='text-align:left!important'>{$trace}</div>
						</div>
						<!-- /error title -->
					</div>
				</div>
				</body>
			</html>";
		$response = new Response($html, 200, [
			'chaset' => 'utf-8',
		]);
		$response->send();
	}
}
