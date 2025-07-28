<?php

namespace App\database\Resources;

class PostResource
{
	public static function collection(array $rows): array
	{
		return array_map([self::class, 'transform'], $rows);
	}

	public static function make(array $post): array
	{
		return self::transform($post);
	}

	public static function transform(array $post): array
	{
		if (empty($post)) {
			return [];
		}
		
		return [
			'id' => $post['id'],
			'title' => $post['title'],
			'slug' => $post['slug'],
			'content' => $post['content'],
			'read_time' => $post['read_time'],
			'featured' => $post['featured'],
			'image_url' => $post['image_url'],
			'available_at' => $post['available_at'],
			'category_id' => $post['category_id'] ? $post['category_id'] : null,
			'category' => $post['cat_id'] ? [
				'id' => $post['cat_id'],
				'title' => $post['cat_title'],
				'slug' => $post['cat_slug']
			] : null
		];
	}
}
