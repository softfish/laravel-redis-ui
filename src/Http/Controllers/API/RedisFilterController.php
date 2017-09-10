<?php
namespace Feikwok\RedisUI\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class RedisFilterController extends Controller {

    /**
     * Filter what we have in the redis server with key and content
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request) {
        if ($request->has('filters')) {
            $filters = $request->get('filters');
            $redis = Redis::connection($request->get('database'));

            if (!empty($redis)) {

                $searchKey = "";
                $searchContent = "";
                foreach ($filters as $fKey => $fStr) {
                    switch($fKey) {
                        case 'key':
                            if (!empty($fStr)) {
                                $searchKey = "{$fStr}";
                            }
                            break;
                        case 'content':
                            if (!empty($fStr)) {
                                $searchContent = "{$fStr}";
                            }
                            break;
                        default;
                    }
                }

                // 1. Firt find the keys matched the filters
                if (empty($searchKey)) {
                    $keys = $redis->keys("*");
                } else {
                    $keys = $redis->keys('*'.$searchKey.'*');
                    if (empty($keys)) {
                        $keys = $redis->keys($searchKey);
                    }
                }

                $data = [];

                // 2. load the keys to the response data.
                $offset = $request->get('offset');
                $currentPage = $request->get('currentPage');
                $fullyMatchedKeys = [];

                foreach ($keys as $index => $key) {
                    $content = $redis->get($key);
                    if (preg_match('/.*' . $searchContent . '.*/i', $content)) {
                        $fullyMatchedKeys[] = [
                            'key' => $key,
                            'content' => $content
                        ];
                    }
                }

                // since we also need to consider the content filter too,
                // so we will do that offset filter here.
                $counter = $offset;
                foreach ($fullyMatchedKeys as $index => $record) {
                    if (($currentPage * $offset) <= $index && $counter != 0) {
                        $data[] = $record;
                        $counter--;
                        if ($counter === 0) {
                            // There is no need to look for more record anymore,
                            // we have enough to display now.
                            break;
                        }
                    }
                }

                return Response::json([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => 'Redis connection failed!'
                ]);
            }
        }
    }

    /**
     * Adding a new key to the given redis database connection
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        // 1. check the key is existed
        if ($request->has('keyname') && $request->has('database')) {
            $redis = Redis::connection($request->get('database'));
            $existingContent = $redis->get($request->get('keyname'));
            if (empty($existingContent)) {
                $redis->set($request->get('keyname'), $request->get('content'));
                return Response::json([
                    'success' => true
                ]);
            } else {
                return Response::json([
                    'success' => false
                ]);
            }
        } else {
            return Response::json([
                'success' => false
            ]);
        }
    }

    /**
     * API update function for an existing key in the given database
     * connection.
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        // 1. check the key is existed
        if ($request->has('keyname') && $request->has('database')) {
            $redis = Redis::connection($request->get('database'));
            $existingContent = $redis->get($request->get('keyname'));

            if (!empty($existingContent)) {
                $redis->del($request->get('keyname'));
                $redis->set($request->get('keyname'), $request->get('content'));
                return Response::json([
                    'success' => true
                ]);
            } else {
                return Response::json([
                    'success' => false
                ]);
            }
        } else {
            return Response::json([
                'success' => false
            ]);
        }
    }

    /**
     * API function to remove an existing key and its content from the given
     * redis database connection.
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        if ($request->has('keyname') && $request->has('database')) {
            $redis = Redis::connection($request->get('database'));
            $redis->del($request->get('keyname'));

            return Response::json([
                'success' => true
            ]);
        } else {
            return Response::json([
                'success' => false
            ]);
        }
    }
}