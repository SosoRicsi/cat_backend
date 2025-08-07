package handler

import (
	"cat_backend/internal/repository"
	"cat_backend/internal/resource"
	"cat_backend/internal/response"
	"log"
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
)

func GetCategories(c *gin.Context) {
	var categories, err = repository.GetCategories()
	if err != nil {
		log.Fatal("CategoryHandler@index", err)
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", err.Error())
		return
	}

	if categories == nil {
		response.JSONError(c, http.StatusNotFound, "Resources not found", "The requested resources was not found")
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"categories": resource.MakeCategories(categories),
		"page":       1,
		"total":      len(categories),
		"showing":    len(categories),
	})
}

func ShowCategory(c *gin.Context) {
	raw_page := c.DefaultQuery("page", "1")
	slug := c.Param("slug")

	page, err := strconv.Atoi(raw_page)
	if err != nil {
		log.Fatal("CategoryHandler@show", err)
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", err.Error())
		return
	}

	categoryWithPosts, err := repository.GetCategoryBySlug(slug, page)
	if err != nil {
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", err.Error())
		return
	}

	if categoryWithPosts == nil {
		response.JSONError(c, http.StatusNotFound, "Resource Not Found", "The requested resource was not found")
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"posts":    resource.MakePosts(categoryWithPosts.Posts),
		"category": resource.MakeCategory(&categoryWithPosts.Category),
		"total":    categoryWithPosts.Total,
		"showing":  len(categoryWithPosts.Posts),
		"pages":    categoryWithPosts.Total / len(categoryWithPosts.Posts),
	})
}
