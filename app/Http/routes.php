<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/

$app->get('v1/docs/{page}', "\App\V1\Controllers\DocController@show");

$app->get('v1/user/subscribe', "\App\V1\Controllers\UserController@subscribe");
$app->get('v1/user/update', "\App\V1\Controllers\UserController@subscribe");


$app->get('v1/list/create', "\App\V1\Controllers\ListController@createList");
$app->get('v1/list/add-fields', "\App\V1\Controllers\ListController@addCustomFields");
$app->get('v1/list/find/{listId}', "\App\V1\Controllers\ListController@getList");