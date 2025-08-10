<?php

use App\database\Resources\CategoryResource;
use App\http\Middlewares\SecretKey;
use App\http\Middlewares\LogRequests;
use App\http\Controllers\PostController;
use App\http\Controllers\CategoryController;
use App\http\Controllers\QuoteController;

$router->get("/quote", function () {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () {
		(new QuoteController)->getRandom();
	});
});

$router->get("/v2/post", function () {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () {
		(new PostController)->index();
	});
});

$router->get("/v2/post/([a-z0-9-]+)", function ($slug) {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () use ($slug) {
		(new PostController)->show($slug);
	});
});

$router->get("/v2/categories", function () {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () {
		(new CategoryController)->index();
	});
});

$router->get("/v2/category/([0-9]+)", function ($id) {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () use ($id) {
		(new CategoryController)->show($id);
	});
});

$router->get('/v2/category/([a-z0-9-]+)', function ($slug) {
	if (!SecretKey::authorize()) {
		http_response_code(403);
		return false;
	}

	print LogRequests::log(function () use ($slug) {
		(new CategoryController)->getBySlug($slug);
	});
});

$router->set404(function () {
	header('HTTP/1.1 404 Not Found');
});
