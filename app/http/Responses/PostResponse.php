<?php

namespace App\http\Responses;

use App\Config\Pagination;

class PostResponse
{

	public static function index(array $recent_posts, array $featured_post, int $page, int $totalPages, int $total): string
	{
		$limit = Pagination::limit();

		http_response_code(200);
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

		return json_encode([
			'posts' => [
				'recent_posts' => $recent_posts,
				'featured_post' => $featured_post
			],
			'page' => $page,
			'pages' => $totalPages,
			'total' => $total,
			'showing' => count($recent_posts),
		]);
	}

	public static function show(array $resource): string
	{
		http_response_code(200);
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Content-Type: application/json');

		return json_encode($resource);
	}

}