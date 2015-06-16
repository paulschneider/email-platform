<?php namespace App\V1\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  AS Console;

class ProcessQueue implements SelfHandling, ShouldBeQueued {
	use InteractsWithQueue, SerializesModels;

	public function handle(\App\V1\Repositories\QueueRepository $queue) {
		$queue->process(new App\Console\Commands\ProcessEmailQueueCommand);
	}
}