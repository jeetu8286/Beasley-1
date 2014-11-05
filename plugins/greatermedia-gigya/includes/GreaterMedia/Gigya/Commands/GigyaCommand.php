<?php

namespace GreaterMedia\Gigya\Commands;

use GreaterMedia\Gigya\FakeProfiles\FakeGigyaUser;
use Faker\Factory;

/**
 * Set of commands to seed fake users into Gigya.
 */
class GigyaCommand extends \WP_CLI_Command {

	/**
	 * Creates fake Gigya Users.
	 *
	 * ## OPTIONS
	 *
	 * <count>
	 * : Number of fake users to create
	 *
	 * <file>
	 * : Path to file to write generated users json
	 *
	 * ## EXAMPLES
	 *
	 * wp gigya seed_users 10
	 *
	 */
	public function seed_users( $args, $opts ) {
		$arg_count = count( $args );
		if ( $arg_count < 2 ) {
			\WP_CLI::error( 'Invalid arguments' );
			return;
		}

		$count = intval( $args[0] );
		$file  = $args[1];

		if ( ! ( is_int( $count ) && $count > 0 ) ) {
			\WP_CLI::error( "Count must be a number > 0 - was $count" );
			return;
		}

		if ( is_null( $file ) ) {
			$file = 'users.json';
		}

		$errors_file = $file . '.errors.log';
		$this->_write_preamble( $file, "[\n" );
		$this->_write_preamble( $errors_file, '' );

		\WP_CLI::success( "Creating $count fake users in Gigya ..." );

		$faker = Factory::create( 'en_US' );

		for ( $i = 0; $i < $count; $i++ ) {
			$user = new FakeGigyaUser();
			$user->seed( $faker );

			try {
				$user->save();

				if ( $count > 1 && $i < $count - 1 ) {
					$user_line  = $user->to_json() . ",\n";
				} else {
					$user_line = $user->to_json() . "\n";
				}

				$this->_write_string( $file, $user_line );
			} catch ( \Exception $e ) {
				\WP_CLI::warning( $e->getMessage() );
				$this->_write_string( $file, $e->getMessage() );
			}
		}

		$this->_write_postamble( $file, ']' );
		\WP_CLI::success( "Creating $count fake users in Gigya ... DONE" );
	}

	public function reset_users( $args, $opts ) {

	}

	private function _write_preamble( $file, $line ) {
		file_put_contents(
			$file, $line
		);
	}

	public function _write_postamble( $file, $line ) {
		file_put_contents(
			$file, $line, FILE_APPEND
		);
	}

	public function _write_string( $file, $line ) {
		file_put_contents(
			$file, $line, FILE_APPEND
		);
	}

}
