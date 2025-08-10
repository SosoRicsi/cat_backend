<?php

use App\Config\App;
use Dotenv\Dotenv;
use App\src\Logger;
use Bramus\Router\Router;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Logger::info("Api accessed.");

$router = new Router;

require __DIR__ . '/web/routes.php';

if (App::env() != "prod") {
	try {
		$router->run();
	} catch (Exception $e) {
		dd(
			[
				'all_headers' => getallheaders(),
				'error' => $e->getMessage()
			]
		);
	}
} else {
	$router->run();
}