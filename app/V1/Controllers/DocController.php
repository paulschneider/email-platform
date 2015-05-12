<?php namespace App\V1\Controllers;

Class DocController extends Controller {
	/**
	 * [home description]
	 * @return [type] [description]
	 */
	public function home() {
		return View("docs.v1.Home");
	}

	/**
	 * [show description]
	 * @param  [type] $page [description]
	 * @return [type]       [description]
	 */
	public function show($page) {
		return View("docs.v1." . $page);
	}
}