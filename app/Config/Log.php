<?php

namespace App\config;

class Log
{

	public static function logPath(): string
	{
		return $_ENV['LOG_PATH'];
	}

	public static function saveIp(): bool
	{
		return $_ENV['LOG_SAVE_IP'] ?? false;
	}

}