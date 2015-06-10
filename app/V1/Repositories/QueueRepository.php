<?php Namespace App\V1\Repositories;

use Carbon\Carbon;
use Db;
use Queue;

Class QueueRepository extends Db {
	/**
	 * the table name to use
	 * @var string
	 */
	protected $table = "request";

	/**
	 * \App\V1\Interfaces\EmailerInterface
	 * @var $mailer
	 */
	protected $mailer;

	/**
	 * App\Console\Commands\ProcessEmailQueueCommand
	 * @var ProcessEmailQueueCommand
	 */
	protected $console;

	/**
	 * class constructor
	 * @param \App\V1\Interfaces\EmailerInterface $mailer
	 */
	public function __construct(\App\V1\Interfaces\EmailerInterface $mailer) {
		$this->mailer = $mailer;
	}

	/**
	 * process request queue stored in the database
	 * @param  $mailer the mailer class to use to subscribe the records
	 * @return null
	 */
	public function process(\App\Console\Commands\ProcessEmailQueueCommand $console) {

		$this->console = $console;

		$queue = DB::table($this->table)->select("*")->limit(1000)->get();

		foreach ($queue AS $item) {

			if (!empty($item->failed_all_attempts)) {
				break;
			}

			# we can accept different clients into the API which would have been stored in the database
			# when the request was first made
			$this->mailer->setClientId($item->client_id);

			# process the subscription request
			$result = $this->mailer->processSubscribe(
				$item->list_id,
				$item->recipient_email,
				$item->recipient_name,
				(array) json_decode($item->fields)
			);

			$this->console->info("Processing responses for user: " . $item->recipient_name);

			# if it was unsuccessful re-add it to the queue
			if (!$result) {
				$this->retry($item);

				$this->console->info("Response failed to submit to the email application.");
			}
			# otherwise remove it from the queue
			else {
				$this->unqueue($item->id);

				$this->console->info("Successfully added to the email application. Removing from queue.");
			}

			$this->console->info("---------------------------------");
		}
	}

	/**
	 * remove a queue row from the database
	 * @param  int $queueId the unique identifier for the row
	 * @return null
	 */
	protected function unqueue($queueId) {
		DB::table($this->table)->where('id', $queueId)->delete();
	}

	/**
	 * set a queue item to be retried
	 * @param  array $queueItem the item to set to retry
	 * @return null
	 */
	protected function retry($queueItem) {
		for ($i = 1; $i <= 3; $i++) {
			$attempt = "attempt_" . $i;

			if (empty($queueItem->{$attempt})) {
				return $this->setAttempt($queueItem, $i);
			}
		}
	}

	/**
	 * set the appropriate attempt count for the row
	 * @param  array $queueItem the database record for the queued item
	 * @return null
	 */
	protected function setAttempt($queueItem, $attempt) {
		$data = [
			"attempt_{$attempt}" => getDateTime(),
		];

		# if this was the final attempt
		if ($attempt == 3) {
			$data['failed_all_attempts'] = true;
			$this->notifyAllFailed();
		}

		# update the database to reflect the attempt
		DB::table($this->table)->where('id', $queueItem->id)->update($data);

		# and request we re-queue the items
		$this->requeue();
	}

	/**
	 * requeue the list so we try and go through it again at a later time
	 * @return null
	 */
	private function requeue() {
		Queue::later(Carbon::now()->addMinutes(5), new \App\V1\Jobs\ProcessQueue(new \App\V1\Repositories\QueueRepository));
	}

	/**
	 * notify system administrators that all subscription attempts have failed
	 * @return null
	 */
	private function notifyAllFailed() {
		Queue::later(Carbon::now()->addMinutes(5), new \App\V1\Jobs\NotifyUnsubscribeable(new this));
		$this->console->error("Notifying all attempts failed.");
	}
}