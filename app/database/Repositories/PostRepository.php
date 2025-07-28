<?php

namespace App\database\Repositories;

use App\src\PDO;

class PostRepository
{
	protected static PDO $db;

	protected static function db(): PDO
	{
		if (!isset(self::$db)) {
			self::$db = PDO::instance();
		}
		return self::$db;
	}

	public static function getLatestPosts(int $limit = 10, int $offset = 0): array
	{
		$limit = (int) $limit;
		$offset = (int) $offset;

		$q = self::db()->select("
            SELECT 
                posts.id,
                posts.title,
                posts.slug,
                posts.content,
                posts.read_time,
                posts.featured,
                posts.image_url,
                posts.available_at,
                posts.category_id,
                categories.id AS cat_id,
                categories.title AS cat_title,
				categories.slug AS cat_slug
            FROM posts
            LEFT JOIN categories ON posts.category_id = categories.id 
            ORDER BY posts.available_at DESC
            LIMIT {$limit} OFFSET {$offset}
        ");

		if (isset($q) && !empty($q)) {
			return $q;
		}

		return [];
	}

	public static function getFeaturedPost(): array
	{
		$q = self::db()->select("
			SELECT 
				posts.id,
                posts.title,
                posts.slug,
                posts.content,
                posts.read_time,
                posts.featured,
                posts.image_url,
                posts.available_at,
                posts.category_id,
                categories.id AS cat_id,
                categories.title AS cat_title,
				categories.slug AS cat_slug
			FROM posts
			LEFT JOIN categories ON posts.category_id = categories.id 
			WHERE posts.featured = 1
			ORDER BY posts.available_at DESC
			LIMIT 1
		");

		if (isset($q[0]) && !empty($q[0])) {
			return $q[0];
		}

		return [];
	}

	public static function getPostsCount(): int
	{
		$q = self::db()->select("
			SELECT COUNT(*) AS total FROM posts
		");

		return $q[0]['total'];
	}

	public static function getPostBySlug(string $slug): array
	{
		$q = self::db()->select("
			SELECT
				posts.id,
                posts.title,
                posts.slug,
                posts.content,
                posts.read_time,
                posts.featured,
                posts.image_url,
                posts.available_at,
                posts.category_id,
                categories.id AS cat_id,
                categories.title AS cat_title,
				categories.slug AS cat_slug
			FROM posts
			LEFT JOIN categories ON posts.category_id = categories.id 
			WHERE posts.slug = ?
			ORDER BY posts.id
			LIMIT 1
		", [$slug]);

		if (isset($q) && !empty($q)) {
			return $q;
		}

		return [];
	}

	public static function getByCategory($id, int $limit = 10, int $offset = 0): array
	{
		$limit = (int) $limit;
		$offset = (int) $offset;

		$q = self::db()->select("
			SELECT 
				posts.id,
				posts.title,
				posts.slug,
				posts.content,
				posts.read_time,
				posts.featured,
				posts.image_url,
				posts.available_at,
				posts.category_id,
				categories.id AS cat_id,
				categories.title AS cat_title,
				categories.slug AS cat_slug
			FROM categories
			LEFT JOIN posts ON categories.id = posts.category_id
			WHERE categories.id = :id
            ORDER BY posts.available_at DESC
            LIMIT {$limit} OFFSET {$offset}
		", [':id' => $id]);

		if (isset($q) && !empty($q)) {
			return $q;
		}

		return [];
	}

	public static function getCountByCategory($id): int
	{
		$q = self::db()->select("
			SELECT count(*) 
			AS total
			FROM posts
			WHERE posts.category_id = :id
		", [':id' => $id]);

		return $q[0]['total'];
	}
}
