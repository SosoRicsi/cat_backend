package resource

import "cat_backend/internal/models"

func MakeCategory(category *models.Category) map[string]interface{} {
	if category == nil {
		return map[string]interface{}{}
	}

	return map[string]interface{}{
		"id":    category.ID,
		"title": category.Title,
		"slug":  category.Slug,
	}
}

func MakeCategories(categories []models.Category) []map[string]interface{} {
	if categories == nil {
		return []map[string]interface{}{}
	}

	var result []map[string]interface{}

	for _, category := range categories {
		result = append(result, MakeCategory(&category))
	}

	return result
}
