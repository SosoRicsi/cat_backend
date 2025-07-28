<?php

namespace App\http\Responses;

class CategoryResponse
{

	public static function index(array $resource, int $page, int $totalPages, int $total, int $showing)
	{
		http_response_code(200);
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

		return json_encode([
			'categories' => $resource,
			'page' => $page,
			'pages' => $totalPages,
			'total' => $total,
			'showing' => $showing
		]);
	}

	public static function show(array $category, array $resource, int $page, int $totalPages, int $total, int $showing)
	{
		http_response_code(200);
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

		return json_encode([
			'category' => $category,
			'posts' => $resource,
			'page' => $page,
			'pages' => $totalPages,
			'total' => $total,
			'showing' => $showing
		]);
	}
}
