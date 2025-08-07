package response

import "github.com/gin-gonic/gin"

func JSONError(c *gin.Context, status int, title string, message string) {
	c.JSON(status, gin.H{
		"error": gin.H{
			"title":   title,
			"message": message,
		},
	})
}
