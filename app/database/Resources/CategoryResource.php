<?php

namespace App\database\Resources;

class CategoryResource
{

	public static function collection(array $rows): array
	{
		return array_map([self::class, 'transform'], $rows);
	}

	public static function make(array $category): array
	{
		return self::transform($category);
	}

	public static function transform(array $category)
	{
		return [
			'id' => $category['id'],
			'title' => $category['title'],
			'slug' => $category['slug']
		];
	}

}