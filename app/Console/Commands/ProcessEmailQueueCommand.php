<?php Namespace App\Console\Commands;

use App\V1\Repositories\QueueRepository;
use App\V1\Repositories\VerificationRepository;
use Illuminate\Console\Command;

Class ProcessEmailQueueCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'email:process {do}';

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'RegistrantProcessor';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process request for users to be registered with the email API';

	/**
	 * [$input description]
	 * @var [type]
	 */
	protected $input;

	/**
	 * VerificationRepository
	 * @var [type]
	 */
	protected $output;

	/**
	 * VerificationRepository
	 * @var App\V1\Repositories\VerificationRepository
	 */
	protected $verificationRepo;

	/**
	 * App\V1\Repositories\QueueRepository
	 * @var QueueRepository
	 */
	protected $queueRepo;

	/**
	 * [__construct description]
	 */
	public function __construct(\App\V1\Interfaces\EmailerInterface $mailer) {
		parent::__construct();

		$mailer->setClientId("c209170c996ff9997059dc429c92f33e");

		$this->verificationRepo = New VerificationRepository();
		$this->queueRepo = New QueueRepository($mailer);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		while ($this->verificationRepo->hasMoreToProcess()) {
			$this->info("---------------------------------");
			$this->info("There are rows to process");

			$this->queueRepo->process($this);
		}
	}
}