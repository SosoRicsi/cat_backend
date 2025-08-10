<?php

namespace App\http\Controllers;

use JarkoRicsi\Quote\Quote;

class QuoteController
{

	public function getRandom()
	{
		$quote = new Quote(__DIR__.'/../../../lang/', 'hu');

		http_response_code(200);
		header('Content-Type: application/json');
		print json_encode($quote->random());
	}

}