<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
 */

/**
 * documentation
 */
$app->get('v1', "\App\V1\Controllers\DocController@home");
$app->get('v1/docs', "\App\V1\Controllers\DocController@home");
$app->get('v1/docs/{page}', "\App\V1\Controllers\DocController@show");

/**
 * users
 */
$app->post('v1/user/subscribe', "\App\V1\Controllers\UserController@subscribe");

/**
 * lists
 */
$app->post('v1/list/create', "\App\V1\Controllers\ListController@createList");
$app->post('v1/list/add-fields', "\App\V1\Controllers\ListController@addCustomFields");
$app->get('v1/list/fields/{listId}', "\App\V1\Controllers\ListController@getCustomFields");
$app->get('v1/list/find/{listId}', "\App\V1\Controllers\ListController@getList");
$app->get('v1/list/all', "\App\V1\Controllers\ListController@getAll");
$app->delete('v1/list/{listId}', "\App\V1\Controllers\ListController@deleteList");
$app->delete('v1/list/all', "\App\V1\Controllers\ListController@deleteAllLists");

/**
 * utilities
 */
$app->get('v1/process', "\App\V1\Controllers\UtilsController@process");
$app->get('v1/mandrill-email', "\App\V1\Controllers\UtilsController@transactionEmail");