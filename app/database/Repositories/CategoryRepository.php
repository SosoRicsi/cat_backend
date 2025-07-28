<?php

namespace App\database\repositories;

use App\src\PDO;
use Exception;
use PDOException;

class CategoryRepository
{

	protected static PDO $db;

	protected static function db(): PDO
	{
		try {
			if (!isset(self::$db)) {
				self::$db = PDO::instance();
			}
			return self::$db;
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function getCategories(int $limit = 10, int $offset = 0)
	{
		try {
			$limit = (int) $limit;
			$offset = (int) $offset;

			return self::db()->select("
				SELECT 
					id,
					title,
					slug
				FROM categories
				LIMIT {$limit} OFFSET {$offset}
			");
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function getCategoriesCount(): int
	{
		try {
			$q = self::db()->select("
				SELECT COUNT(*) AS total FROM categories
			");

			return $q[0]['total'];
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function getCategoryById(int $id): array
	{
		try {
			return self::db()->select("
				SELECT 
					id, 
					title, 
					slug
				FROM categories
				WHERE categories.id = :id
			", [':id' => $id]);
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function exists(int $id): bool
	{
		try {
			$result = self::db()->select("
				SELECT 1 
				FROM categories 
				WHERE id = :id 
				LIMIT 1
			", [':id' => $id]);

			return !empty($result);
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function getCategoryBySlug(string $slug): array
	{
		try {
			$q = self::db()->select("
				SELECT 
					id, 
					title, 
					slug
				FROM categories
				WHERE categories.slug = :slug
			", [':slug' => $slug]);

			return $q[0];
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}
	}
}
