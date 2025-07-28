<?php

namespace App\http\Controllers;

use App\Config\Pagination;
use App\database\Resources\CategoryResource;
use App\database\Repositories\CategoryRepository;
use App\database\Repositories\PostRepository;
use App\database\Resources\PostResource;
use App\http\Responses\CategoryResponse;
use App\http\Responses\ErrorResponse;
use App\src\Logger;
use PDOException;
use Exception;

class CategoryController
{
	public function index()
	{
		try {
			$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
			$limit = Pagination::limit();
			$offset = ($page - 1) * $limit;

			$categories = CategoryRepository::getCategories($limit, $offset);
			$resource = CategoryResource::collection($categories);
			$total = CategoryRepository::getCategoriesCount();
			$totalPages = ceil($total / $limit);

			print CategoryResponse::index(
				$resource,
				$page,
				$totalPages,
				$total,
				$total === 0 ? 0 : ($limit > $total ? $total : $limit)
			);
		} catch (PDOException $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => bin2hex(random_bytes(8)) . "- " . "CategoryController@index" . " -" . bin2hex(random_bytes(8)),
				]
			);

			print ErrorResponse::index(
				500,
				'Database error',
				$e->getMessage()
			);
		} catch (Exception $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "CategoryController@index" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(
				500,
				'Internal server error',
				$e->getMessage()
			);
		}
	}

	public function show($id)
	{
		try {
			$resource = [];

			if (!CategoryRepository::exists($id)) {
				print ErrorResponse::index(
					404,
					"Resource not found",
					"The requested category was not found!"
				);
				return;
			}

			$page = Pagination::page();
			$limit = Pagination::limit();
			$offset = Pagination::offset();

			$category = CategoryRepository::getCategoryById($id);
			$posts = PostRepository::getByCategory($id, $limit, $offset);

			if ($posts[0]['id'] != null) {
				$resource = PostResource::collection($posts);
			}


			$total = PostRepository::getCountByCategory($id);
			$totalPages = ceil($total / $limit);

			print CategoryResponse::show(
				$category[0] ?? ['id' => $id, 'title' => null],
				$resource,
				$page,
				$totalPages,
				$total,
				$total === 0 ? 0 : ($limit > $total ? $total : $limit)
			);
		} catch (PDOException $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "CategoryController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Database error', $e->getMessage());
		} catch (Exception $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "CategoryController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Internal Server Error', $e->getMessage());
		}
	}

	public function getBySlug(string $slug)
	{
		try {
			$resource = [];

			if (!$category = CategoryRepository::getCategoryBySlug($slug)) {
				print ErrorResponse::index(
					404,
					"Resource not found",
					"The requested category was not found!"
				);
				return;
			}
			$id = $category['id'];

			if (!CategoryRepository::exists($id)) {
				print ErrorResponse::index(
					404,
					"Resource not found",
					"The requested category was not found!"
				);
				return;
			}

			$page = Pagination::page();
			$limit = Pagination::limit();
			$offset = Pagination::offset();

			$posts = PostRepository::getByCategory($id, $limit, $offset);

			if ($posts[0]['id'] != null) {
				$resource = PostResource::collection($posts);
			}


			$total = PostRepository::getCountByCategory($id);
			$totalPages = ceil($total / $limit);

			print CategoryResponse::show(
				$category ?? ['id' => $id, 'title' => null],
				$resource,
				$page,
				$totalPages,
				$total,
				$total === 0 ? 0 : ($limit > $total ? $total : $limit)
			);
		} catch (PDOException $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "CategoryController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Database error', $e->getMessage());
		} catch (Exception $e) {
			Logger::error(
				$e->getMessage(),
				[
					'error_code' => "CategoryController@show" . bin2hex(random_bytes(8))
				]
			);

			print ErrorResponse::index(500, 'Internal Server Error', $e->getMessage());
		}
	}
}
