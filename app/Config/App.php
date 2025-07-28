<?php

namespace App\Config;

class App
{

	public static function name(): string
	{
		return $_ENV["APP_NAME"];
	}

	public static function version(): int
	{
		return $_ENV['APP_VERSION'];
	}

	public static function env(): string
	{
		return $_ENV["APP_ENV"] ?? "prod";
	}

	public static function appSecretKey(): string
	{
		return $_ENV['APP_SECRET_KEY'];
	}

}