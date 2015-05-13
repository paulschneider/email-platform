<?php Namespace App\V1\Events;

use Carbon\Carbon;
use \Illuminate\Contracts\Queue\ShouldBeQueued;
use \Queue;

Class RequestWasLogged implements ShouldBeQueued {

	use \Illuminate\Queue\InteractsWithQueue;

	public function handle() {
		$date = Carbon::now()->addSeconds(30);
		Queue::later($date, new \App\V1\Jobs\ProcessQueue(new \App\V1\Repositories\QueueRepository));
	}
}