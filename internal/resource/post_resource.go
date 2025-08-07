package resource

import "cat_backend/internal/models"

func MakePost(post *models.Post) map[string]interface{} {
	if post == nil {
		return map[string]interface{}{}
	}

	var category map[string]interface{}
	if post.Category.ID != 0 {
		category = MakeCategory(&post.Category)
	}

	return map[string]interface{}{
		"id":           post.ID,
		"title":        post.Title,
		"slug":         post.Slug,
		"content":      post.Content,
		"read_time":    post.ReadTime,
		"featured":     post.Featured,
		"image_url":    post.ImageURL,
		"available_at": post.AvailableAt,
		"category_id":  post.CategoryID,
		"category":     category,
	}
}

func MakePosts(posts []models.Post) []map[string]interface{} {
	if posts == nil {
		return []map[string]interface{}{}
	}

	var result []map[string]interface{}
	for _, post := range posts {
		result = append(result, MakePost(&post))
	}

	return result
}
