<?php namespace App\V1\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUnsubscribeable implements SelfHandling, ShouldBeQueued {
	use InteractsWithQueue, SerializesModels;

	public function handle(\App\V1\Repositories\QueueRepository $queue) {
		$queue->process();
	}
}
