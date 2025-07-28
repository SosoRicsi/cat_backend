<?php

namespace App\Config;

class Database
{

	public static function driver(): string
	{
		return $_ENV["DATABASE_DRIVER"];
	}

	public static function host(): string
	{
		return $_ENV["DATABASE_HOST"];
	}

	public static function user(): string
	{
		return $_ENV["DATABASE_USER"];
	}

	public static function password(): string
	{
		return $_ENV["DATABASE_PASSWORD"];
	}

	public static function database(): string
	{
		return $_ENV["DATABASE"];
	}

	public static function port(): string
	{
		return $_ENV["DATABASE_PORT"];
	}

	public static function charset(): string
	{
		return $_ENV["DATABASE_CHARSET"];
	}

}