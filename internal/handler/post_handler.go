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

func GetPosts(c *gin.Context) {
	var raw_page = c.DefaultQuery("page", "1")
	page, err := strconv.Atoi(raw_page)
	if err != nil {
		log.Fatal("PostHandler@index", err)
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", err.Error())
		return
	}

	var recent_posts, featured_post, total_posts, query_error = repository.GetPosts(page)

	if query_error != nil {
		log.Fatal("PostHandler@index", query_error)
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", query_error.Error())
		return
	}

	if recent_posts == nil {
		response.JSONError(c, http.StatusNotFound, "Resource not found", "The requested resource was not found!")
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"posts": gin.H{
			"recent_posts":  resource.MakePosts(recent_posts),
			"featured_post": resource.MakePost(featured_post),
		},
		"page":    page,
		"total":   total_posts,
		"showing": len(recent_posts),
	})
}

func ShowPost(c *gin.Context) {
	var slug string = c.Param("slug")

	var post, err = repository.GetPostBySlug(slug)
	if err != nil {
		log.Fatal("PostHandler@show", err)
		response.JSONError(c, http.StatusInternalServerError, "Internal Server Error", err.Error())
		return
	}

	if post == nil {
		response.JSONError(c, http.StatusNotFound, "Resource not found", "The requested resource was not found!")
		return
	}

	c.JSON(http.StatusOK, resource.MakePost(post))
}
