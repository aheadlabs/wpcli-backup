<?php /** @noinspection PhpUnhandledExceptionInspection */

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Performs WordPress backup operations
 */
class Backup_Commands extends WP_CLI_Command
{
	private string $db_prefix;

	public function __construct()
	{
		parent::__construct();
		$this->db_prefix = $this->get_db_table_prefix();
	}

	/**
	 * Creates a dump file after removing transients from the *options table
	 *
	 * ## OPTIONS
	 *
	 * <dump_path>
	 * : The path to the dump to be created.
	 *
	 * [--skip-transient-removal]
	 * : If present it skips the transients removal before creating the dump
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function create($args, $assoc_args)
	{
		WP_CLI::line(_("Backing up the WordPress site..."));
		WP_CLI::line(date("Y.m.d-Hisve"));

		if (!$assoc_args["skip-transient-removal"])
			$this->reset_transients();

		WP_CLI::runcommand("db export {$args[0]} --extended-insert=false --path=3");

		WP_CLI::runcommand("cli info");
	}

	private function get_db_table_prefix(): string
	{
		return WP_CLI::runcommand("db prefix", array("return"=>true));
	}

	private function generate_dump_file_name(string $file_path): string
	{

		$file_name = date("Y.m.d");
		return $file_name;
	}

	private function reset_transients()
	{
		WP_CLI::log(_("Deleting transients"));

		$options = array(
		  'return'     => "all",   // Return 'STDOUT'; use 'all' for full object.
		  //'parse'      => 'json', // Parse captured STDOUT to JSON array.
		  'launch'     => false,  // Reuse the current process.
		  'exit_error' => true,   // Halt script execution on error.
		);

		$output = WP_CLI::runcommand("transient delete --all", $options);
		WP_CLI::log($output->stdout);
	}
}

WP_CLI::add_command("backup", "Backup_Commands");
