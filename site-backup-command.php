<?php /** @noinspection PhpUnhandledExceptionInspection */

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Performs WordPress backup operations
 */
class Backup_Command extends WP_CLI_Command
{
	public function backup()
	{
		WP_CLI::success("Starting backup");
	}
}
WP_CLI::add_command("aheadlabs", "Backup_Command");
