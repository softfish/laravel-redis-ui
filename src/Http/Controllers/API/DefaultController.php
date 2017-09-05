<?php
namespace Feikwok\RedisUI\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class DefaultController extends Controller 
{
	public function getAvailableDatabase ()
	{
		// Let find out what connections are available in the
		// laravel config.
		$dbConfig = config('database.redis');
		$databases = [];
		foreach ($dbConfig as $key => $value) {
			if (isset($value['host'])) {
				$databases[] = $key;
			}
		}

		return Response::json([
			'success' => true,
			'databases' => $databases
		]);
	}
}