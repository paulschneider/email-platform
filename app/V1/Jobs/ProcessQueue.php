<?php namespace App\V1\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQueue implements SelfHandling, ShouldBeQueued {
	use InteractsWithQueue, SerializesModels;

	/**
	 * console object
	 * @var \App\Console\Commands\ProcessEmailQueueCommand
	 */
	protected $console;

	public function __construct(\App\V1\Interfaces\EmailerInterface $mailer) {
		$this->console = New \App\Console\Commands\ProcessEmailQueueCommand($mailer);
		parent::__construct();
	}

	public function handle(\App\V1\Repositories\QueueRepository $queue) {
		$queue->process($this->console);
	}
}