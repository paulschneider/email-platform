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
	 * [$mailer description]
	 * @var [type]
	 */
	protected $mailer;

	/**
	 * [__construct description]
	 * @param \App\V1\Interfaces\EmailerInterface $mailer [description]
	 */
	public function __construct(\App\V1\Interfaces\EmailerInterface $mailer) {
		$this->mailer = $mailer;
	}

	/**
	 * process request queue stored in the database
	 * @param  $mailer the mailer class to use to subscribe the records
	 * @return null
	 */
	public function process() {
		$queue = DB::table($this->table)->select("*")->get();

		foreach ($queue AS $item) {

			if (!empty($item->failed_all_attempts)) {
				break;
			}

			# process the subscription request
			$result = $this->mailer->processSubscribe(
				$item->list_id,
				$item->recipient_email,
				$item->recipient_name,
				(array) json_decode($item->fields)
			);

			# if it was unsuccessful re-add it to the queue
			if (!$result) {
				$this->retry($item);
			}
			# otherwise remove it from the queue
			else {
				$this->unqueue($item->id);
			}
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
}