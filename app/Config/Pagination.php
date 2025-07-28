<?php

namespace App\Config;

class Pagination
{

	public static function limit(): int
	{
		return 9;
	}

	public static function page(): int
	{
		return isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
	}

	public static function offset(): int
	{
		return (self::page() - 1) * self::limit();
	}
}
