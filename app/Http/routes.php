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
$app->get('v1/docs', "\App\V1\Controllers\DocController@home");
$app->get('v1/docs/{page}', "\App\V1\Controllers\DocController@show");

$app->get('v1/process', "\App\V1\Controllers\UtilsController@process");
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