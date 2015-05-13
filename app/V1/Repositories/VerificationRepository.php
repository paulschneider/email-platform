<?php Namespace App\V1\Repositories;

use \Db;

Class VerificationRepository extends Db {

	/**
	 * the table name to use
	 * @var string
	 */
	protected $table = "request";

	/**
	 * store some data in the database
	 * @param  array $data the data to insert into the database
	 * @return boolean
	 */
	public function store($data = []) {
		if (is_array($data)) {
			return DB::table($this->table)->insert($data);
		}
		return false;
	}
}