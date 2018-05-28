<?php

Route::group(['prefix' => 'redis-ui'], function(){
    Route::get('/', '\Feikwok\RedisUI\Http\Controllers\UIController@index')->middleware(['web', 'auth']);

    Route::group(['prefix' => 'api'], function(){
        Route::post('filters', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@index');
        Route::post('delete', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@delete');
        Route::post('create', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@add');
        Route::post('edit', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@update');
        Route::get('get-db', '\Feikwok\RedisUI\Http\Controllers\API\DefaultController@getAvailableDatabase');
    });
});