package repository

import (
	"cat_backend/internal/database"
	"cat_backend/internal/models"
	"database/sql"
	"fmt"
)

func GetCategories() ([]models.Category, error) {
	var categories []models.Category
	var query string = `
		SELECT
			id,
			title,
			slug
		FROM categories
	`

	var rows, query_error = database.DB.Query(query)

	if query_error != nil {
		return nil, query_error
	}

	defer rows.Close()

	for rows.Next() {
		var category models.Category

		var for_error error = rows.Scan(&category.ID, &category.Title, &category.Slug)
		if for_error != nil {
			return nil, for_error
		}

		categories = append(categories, category)
	}

	var rows_error error = rows.Err()
	if rows_error != nil {
		return nil, rows_error
	}

	return categories, nil
}

type CategoryWithPosts struct {
	Category models.Category
	Posts    []models.Post
	Total    int
}

func GetCategoryBySlug(slug string, page int) (*CategoryWithPosts, error) {
	var category models.Category
	var posts []models.Post
	var err error

	category_query := `
		SELECT
			id,
			title,
			slug
		FROM categories
		WHERE slug = ?
		LIMIT 1
	`

	err = database.DB.QueryRow(category_query, slug).Scan(&category.ID, &category.Title, &category.Slug)

	if err == sql.ErrNoRows {
		return nil, nil
	}

	if err != nil {
		return nil, err
	}

	var total int
	err = database.DB.QueryRow("SELECT COUNT(*) FROM posts WHERE category_id = ?", category.ID).Scan(&total)
	if err != nil {
		return nil, err
	}

	if total > 0 {
		if page < 1 {
			page = 1
		}

		var limit int = 9
		var offset int = (page - 1) * limit

		posts_query := fmt.Sprintf(`
			SELECT
				posts.id,
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
				categories.slug as cat_slug
			FROM posts
			LEFT JOIN categories ON categories.id = posts.category_id
			WHERE posts.category_id = ?
			LIMIT %d
			OFFSET %d
		`, limit, offset)

		var rows, err = database.DB.Query(posts_query, category.ID)
		if err != nil {
			return nil, err
		}
		defer rows.Close()

		for rows.Next() {
			var post models.Post

			err := rows.Scan(
				&post.ID, &post.Title, &post.Slug, &post.Content,
				&post.ReadTime, &post.Featured, &post.ImageURL,
				&post.AvailableAt, &post.CategoryID,
				&post.Category.ID, &post.Category.Title, &post.Category.Slug,
			)
			if err != nil {
				return nil, err
			}

			posts = append(posts, post)
		}
	}

	return &CategoryWithPosts{
		Category: category,
		Posts:    posts,
		Total:    total,
	}, nil
}
