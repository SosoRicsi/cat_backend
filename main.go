package main

import (
	"cat_backend/internal/database"
	"cat_backend/internal/handler"
	"cat_backend/internal/middleware"
	"log"
	"net/http"
	"os"

	"github.com/common-nighthawk/go-figure"
	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
)

func main() {
	var env_error error = godotenv.Load()

	if env_error != nil {
		log.Fatal(env_error)
	}

	figure.NewFigure(os.Getenv("APP_NAME"), "isometric1", true).Print()

	database.Init()
	defer database.DB.Close()
	log.Println("✅ Database connected!")

	router := gin.Default()
	router.Use(middleware.AuthorizeSecretKey())

	router.GET("/ping", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"message": "pong",
		})
	})

	router.GET("/categories", handler.GetCategories)
	router.GET("/post", handler.GetPosts)
	router.GET("/category/:slug", handler.ShowCategory)
	router.GET("/post/:slug", handler.ShowPost)

	router.Run()
}
