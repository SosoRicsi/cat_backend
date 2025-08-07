package middleware

import (
	"log"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

func AuthorizeSecretKey() gin.HandlerFunc {
	return func(c *gin.Context) {
		if os.Getenv("APP_ENV") == "local" {
			c.Next()
			return
		}

		var secret_token string = c.GetHeader("X-SECRET-TOKEN")
		if secret_token == os.Getenv("APP_SECRET_TOKEN") {
			c.Next()
			return
		}

		log.Println("Api authorization failed")
		c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{
			"error": "Unauthorized",
		})
	}
}
