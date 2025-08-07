package database

import (
	"database/sql"
	"log"

	_ "github.com/go-sql-driver/mysql"
)

var DB *sql.DB

func Init() {
	var err error
	DB, err = sql.Open("mysql", "root:root@tcp(localhost:3306)/teszt")

	if err != nil {
		log.Fatalf("Database init error: %v", err)
	}
}
