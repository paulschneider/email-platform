<?php namespace App\V1\Controllers;

Class DocController extends Controller
{	
	/**
	 * [show description]
	 * @param  [type] $page [description]
	 * @return [type]       [description]
	 */
	public function show($page)
	{
		return View("Docs.V1." . $page);
	}
}