<?php

namespace App\http\Middlewares;

use App\Config\App;
use App\src\Logger;

class SecretKey
{

	public static function authorize(): bool
	{
		if (App::env() === "local") {
			return true;
		}

		if (App::appSecretKey() === getHeader('X-SECRET-TOKEN', "")) {
			return true;
		}

		Logger::error(
			"Api authorization failed",
			[]
		);
		return false;
	}

}