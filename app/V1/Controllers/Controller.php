<?php namespace App\V1\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
Use \App\V1\Interfaces\EmailerInterface AS Mailer;

class Controller extends BaseController
{	
	/**
	 * instance of the mailer class
	 * @var Mailer
	 */
    protected $mailer;

    /**
     * class constructor
     * @param Mailer $mailer instance of the mailer class
     */
    public function __construct(Mailer $mailer)
	{
		$this->mailer = $mailer;		
	}
}