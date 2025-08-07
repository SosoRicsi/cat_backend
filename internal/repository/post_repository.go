package repository

import (
	"cat_backend/internal/database"
	"cat_backend/internal/models"
	"database/sql"
	"fmt"
)

var posts_select string = `posts.id,
			posts.title,
			posts.slug,
			posts.content,
			posts.read_time,
			posts.featured,
			posts.image_url,
			COALESCE(posts.available_at, posts.created_at) as available_at,
			posts.category_id,
			categories.id as cat_id,
			categories.title as cat_title,
			categories.slug as cat_slug`

func GetPosts(page int) ([]models.Post, *models.Post, int, error) {
	if page < 1 {
		page = 1
	}

	var total int
	var total_query_error error = database.DB.QueryRow("SELECT COUNT(*) FROM posts").Scan(&total)
	if total_query_error != nil {
		return nil, nil, 0, total_query_error
	}

	var posts []models.Post
	var limit int = 9
	var offset int = (page - 1) * limit

	var query string = fmt.Sprintf(`
		SELECT
			%v
		FROM posts
		LEFT JOIN categories ON categories.id = posts.category_id
		WHERE posts.featured = 0
		ORDER BY posts.available_at DESC
		LIMIT %d
		OFFSET %d
	`, posts_select, limit, offset)

	var rows, query_errors = database.DB.Query(query)

	if query_errors != nil {
		return nil, nil, 0, query_errors
	}
	defer rows.Close()

	for rows.Next() {
		var post models.Post

		var scan_error error = rows.Scan(
			&post.ID, &post.Title, &post.Slug, &post.Content,
			&post.ReadTime, &post.Featured, &post.ImageURL,
			&post.AvailableAt, &post.CategoryID,
			&post.Category.ID, &post.Category.Title, &post.Category.Slug,
		)
		if scan_error != nil {
			return nil, nil, 0, scan_error
		}

		posts = append(posts, post)
	}

	var rows_error = rows.Err()
	if rows_error != nil {
		return nil, nil, 0, rows_error
	}

	var featured_post models.Post
	var featured_query string = fmt.Sprintf(`
		SELECT
			%v
		FROM posts
		LEFT JOIN categories ON categories.id = posts.category_id
		WHERE posts.featured = 1
		ORDER BY posts.available_at DESC
		LIMIT 1
	`, posts_select)

	var featured_query_error error = database.DB.QueryRow(featured_query).Scan(
		&featured_post.ID, &featured_post.Title, &featured_post.Slug, &featured_post.Content,
		&featured_post.ReadTime, &featured_post.Featured, &featured_post.ImageURL,
		&featured_post.AvailableAt, &featured_post.CategoryID,
		&featured_post.Category.ID, &featured_post.Category.Title, &featured_post.Category.Slug,
	)

	if featured_query_error != nil {
		return nil, nil, 0, featured_query_error
	}

	return posts, &featured_post, total, nil
}

func GetPostBySlug(slug string) (*models.Post, error) {
	var post models.Post
	var query string = `
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
			categories.id as cat_id,
			categories.title as cat_title,
			categories.slug as cat_slug
		FROM posts
		LEFT JOIN categories ON categories.id = posts.category_id
		WHERE posts.slug = ?
		LIMIT 1
	`

	var err = database.DB.QueryRow(query, slug).Scan(
		&post.ID, &post.Title, &post.Slug, &post.Content,
		&post.ReadTime, &post.Featured, &post.ImageURL,
		&post.AvailableAt, &post.CategoryID,
		&post.Category.ID, &post.Category.Title, &post.Category.Slug,
	)

	if err == sql.ErrNoRows {
		return nil, nil
	}

	if err != nil {
		return nil, err
	}

	return &post, nil
}
