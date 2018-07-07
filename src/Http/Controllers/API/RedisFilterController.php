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
                // 1. Firt find the keys matched the filters
                list($keys, $searchContent) = $this->findKeysAndSearchContent($filters, $redis);
                $data = [];

                // 2. load the keys to the response data.
                $offset = $request->get('offset');
                $currentPage = $request->get('currentPage');
                $fullyMatchedKeys = $this->getFullyMatchedKeys($redis, $keys, $searchContent);

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

                $hasNextPage = ((count($fullyMatchedKeys)/$offset) > ($currentPage +1))? true : false;

                return Response::json([
                    'success' => true,
                    'data' => $data,
                    'totalPage' => count($fullyMatchedKeys) / $offset,
                    'hasNextPage' => $hasNextPage
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
                    'success' => false,
                    'message' => 'Key already existed, please use update or create a different key.'
                ]);
            }
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Missing mandatory key name and target database.'
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
                    'success' => false,
                    'message' => 'Key not found.'
                ]);
            }
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Missing mandatory key name and target database.'
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
                'success' => false,
                'message' => 'Missing mandatory key name and target database.'
            ]);
        }
    }

    /**
     * Get the fully matched key if we have been given searchContent
     *
     * @param $redis
     * @param $keys
     * @param $searchContent
     * @return array
     */
    protected function getFullyMatchedKeys($redis, $keys, $searchContent)
    {
        $fullyMatchedKeys = [];

        foreach ($keys as $index => $key) {
            $keyType = $redis->type($key)->__toString();
            switch ($keyType) {
                case "string":
                    $content = $redis->get($key);
                    break;
                case "list":
                    $content = substr(json_encode($redis->lrange($key, 0, -1)), 0, 100).'... more';
                    break;
                case "zset":
                    $content = substr(json_encode($redis->zrange($key, 0, -1)), 0, 100).'... more';
                    break;
                default:
                    $content = 'Unhandled Type: '.$keyType;
            }

            if (preg_match('/.*' . $searchContent . '.*/i', $content)) {
                $fullyMatchedKeys[] = [
                    'key' => $key,
                    'content' => $content
                ];
            }
        }

        return $fullyMatchedKeys;
    }

    /**
     * Find the right keys from a given filters
     *
     * @param $filters
     * @param $redis
     * @return mixed
     */
    protected function findKeysAndSearchContent($filters, $redis)
    {
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


        if (empty($searchKey)) {
            $keys = $redis->keys("*");
        } else {
            $keys = $redis->keys('*'.$searchKey.'*');
            if (empty($keys)) {
                $keys = $redis->keys($searchKey);
            }
        }

        return [$keys, $searchContent];
    }
}