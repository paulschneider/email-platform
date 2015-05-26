<?php namespace App\V1\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUnsubscribeable implements SelfHandling, ShouldBeQueued {
	use InteractsWithQueue, SerializesModels;

	/**
	 * handle
	 * @param  \App\V1\Repositories\QueueRepository $queue
	 * @return null
	 */
	public function handle(\App\V1\Mailers\Mandrill $mailer) {
		$mailer->send(new \App\V1\Mailers\Mandrill\Unsubscribeable([
			"email" => "pschneider@theagencyonline.co.uk",
			"name" => "Paul Schneider",
		]));
	}
}