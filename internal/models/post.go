package models

type Post struct {
	ID          int
	Title       string
	Slug        string
	Content     string
	ReadTime    string
	Featured    bool
	ImageURL    string
	AvailableAt *string
	CategoryID  *int
	Category    Category
}
