<?php

namespace App\http\Middlewares;

use App\src\Logger;

class LogRequests
{

	public static function log(mixed $controller)
	{
		ob_start();
		$controller();
		$response = ob_get_clean();

		Logger::info(
			"Default request log",
			[
				'headers' => getallheaders(),
				'response' => $response,
				'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
				'api_token' => $_SERVER['HTTP_X_SECRET_TOKEN'] ?? 'not provided'
			]
		);

		return $response;
	}

}