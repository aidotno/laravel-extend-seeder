<?php

class CsvSeederTest extends \TestCase {

	/**
	 * Migrate database.
	 */
	public function runDatabaseMigrations()
	{
		// Create our testing DB tables
		$this->artisan('migrate', [
			'--path' => 'vendor/lanin/laravel-csv-seeder/tests/migrations',
		]);
	}

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		// Use an in-memory DB
		$this->app['config']->set('database.default', 'csv_test');
		$this->app['config']->set('database.connections.csv_test', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
		]);

		$this->runDatabaseMigrations();
	}

	public function test_seeding()
	{
		$this->seed('CsvSeederDatabaseSeeder');

		$this->seeInDatabase('accounts_csv_seeder', ['login' => 'john.doe']);
	}
}


class CsvSeederDatabaseSeeder extends \Lanin\CsvSeeder\CsvSeeder {

	/**
	 * Boot seeder.
	 */
	protected function boot()
	{
		parent::boot();

		self::setCsvPath(__DIR__ . '/csv');
	}

	/**
	 * Seed your database.
	 */
	public function run()
	{
		$this->seedModel(AccountCsvSeeder::class);
	}
}

class AccountsCsvSeederTableSeeder extends \Lanin\CsvSeeder\CsvSeeder {

	/**
	 * Overwrite database name.
	 *
	 * @var string
	 */
	protected $database = 'tests';

	/**
	 * Seed model with CSV.
	 */
	public function run()
	{
		$this->seedWithCsv();
	}
}

class AccountCsvSeeder extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'accounts_csv_seeder';
}
