<?php

namespace App\http\Responses;

class ErrorResponse
{

	public static function index(int $code, string $error, mixed $message)
	{
		http_response_code($code);
		header('Content-Type: application/json');

		return json_encode([
			'error' => $error,
			'message' => $message
		]);
	}
}
