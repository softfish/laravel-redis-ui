<?php

Route::group(['prefix' => 'redis-ui'], function(){
    Route::resource('/', \Feikwok\RedisUI\Http\Controllers\UIController::class);

    Route::group(['prefix' => 'api'], function(){
        Route::post('filters', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@index');
        Route::post('delete', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@delete');
        Route::post('create', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@add');
        Route::post('edit', '\Feikwok\RedisUI\Http\Controllers\API\RedisFilterController@update');
    });
});