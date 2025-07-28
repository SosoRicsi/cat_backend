<?php

namespace App\src;

use App\Config\Log as LogConfig;

class Logger
{
	protected static string $logPath;

	protected static function ensureLogPath(): void
	{
		if (!isset(self::$logPath)) {
			self::$logPath = rtrim(LogConfig::logPath(), '/') . '/';
		}
	}

	protected static function getLogFile(string $baseName): string
	{
		self::ensureLogPath();
		$date = date('Y-m-d');
		return self::$logPath . "{$baseName}-{$date}.log";
	}

	public static function log(string $level, string $message, array $context = [], string $baseFile = 'app'): void
	{
		$file = self::getLogFile($baseFile);
		$dateTime = date('Y-m-d H:i:s');

		$entry = [
			'timestamp' => $dateTime,
			'level' => strtoupper($level),
			'message' => $message,
			'context' => $context,
		];

		if (LogConfig::saveIp()) {
			$entry['remote_addr'] = $_SERVER['REMOTE_ADDR'];
		};

		if (!is_dir(dirname($file))) {
			mkdir(dirname($file), 0775, true);
		}

		file_put_contents($file, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
	}

	public static function info(string $message, array $context = [], string $baseFile = 'app'): void
	{
		self::log('info', $message, $context, $baseFile);
	}

	public static function warning(string $message, array $context = [], string $baseFile = 'app'): void
	{
		self::log('warning', $message, $context, $baseFile);
	}

	public static function error(string $message, array $context = [], string $baseFile = 'app'): void
	{
		self::log('error', $message, $context, $baseFile);
	}

	public static function getLogPath(): string
	{
		return self::$logPath;
	}
}
