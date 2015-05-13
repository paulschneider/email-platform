<?php namespace App\V1\Controllers;

Class UtilsController extends Controller {
	public function process(\App\V1\Repositories\QueueRepository $repo) {
		$repo->process();
	}
}