<?php

use App\src\PDO;

if (!function_exists("db")) {
	function db(): PDO
	{
		return PDO::instance();
	}
}

if (!function_exists("getHeader")) {
	function getHeader(string $targetKey, mixed $default = null): ?string
	{
		foreach (getallheaders() as $key => $value) {
			if (strtolower($key) === strtolower($targetKey)) {
				return $value;
			}
		}
		return $default;
	}
}
