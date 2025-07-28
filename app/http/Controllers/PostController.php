<?php

namespace App\http\Controllers;

use App\Config\Pagination;
use Exception;
use PDOException;
use App\src\Logger;
use App\http\Responses\PostResponse;
use App\http\Responses\ErrorResponse;
use App\database\Resources\PostResource;
use App\database\Repositories\PostRepository;

class PostController
{

	public function index()
	{
		try {
			$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
			$limit = Pagination::limit();
			$offset = ($page - 1) * $limit;
	
			$recent_posts = PostResource::collection(PostRepository::getLatestPosts($limit, $offset));
			$featured_post = PostResource::make(PostRepository::getFeaturedPost());
			$total = PostRepository::getPostsCount();
			$totalPages = ceil($total / $limit);
	
			print PostResponse::index(
				$recent_posts,
				$featured_post,
				$page,
				$totalPages,
				$total,
			);
		}  catch (PDOException $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "PostController@index" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Database error', $e->getMessage());
		} catch (Exception $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "PostController@index" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Internal Server Error', $e->getMessage());
		}
	}

	public function show($slug)
	{
		try {
			$posts = PostRepository::getPostBySlug($slug);
			$post = $posts[0] ?? null;
	
			if (!$post) {
				print ErrorResponse::index(
					404,
					"Resource not found",
					"The requested resource was not found!"
				);
				
				return;
			}
	
			print PostResponse::show(PostResource::make($post));
		} catch (PDOException $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "PostController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Database error', $e->getMessage());
		} catch (Exception $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "PostController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Internal Server Error', $e->getMessage());
		}
	}
}
