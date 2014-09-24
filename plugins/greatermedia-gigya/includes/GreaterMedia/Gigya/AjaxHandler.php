<?php

namespace GreaterMedia\Gigya;

/**
 * AjaxHandler is the base class for all `wp_ajax` callbacks.
 *
 * All Ajax callbacks have the following responsibilities.
 *
 * 1. Checking referer ( for non public ajax )
 * 2. Verify nonce.
 * 3. Verify privileges/capabilities.
 * 4. Extracting parameters passed to AJAX in POST.
 * 5. Do some work based on the parameters specified.
 * 6. Return JSON as success.
 * 7. Or Return JSON as error.
 *
 * This class wraps all this so that you only need to implement the 'work' step.
 * The rest is taken care of for you in a declarative manner. You only
 * need to fill in some attributes via abstract methods.
 *
 * Minimum implementation needed in subclasses is,
 *
 * 1. run() - with optional return statement.
 * 2. get_action() - the action name to call from the client.
 *
 * @package GreaterMedia\Gigya
 */
class AjaxHandler {

	/**
	 * Stores whether a json response has been sent to the client.
	 *
	 * @access public
	 * @var boolean
	 */
	public $did_send_json = false;

	/**
	 * Abstract method that returns the name of the ajax action to register
	 * with WordPress.
	 *
	 * An action name 'foo' corresponds to 'wp_ajax_foo'.
	 *
	 * @access public
	 * @abstract
	 * @return string The name of the ajax action
	 */
	public function get_action() {
		return 'handler_action_name';
	}

	/**
	 * Indicates whether the handler is public. Default handlers are
	 * private.
	 *
	 * Public AJAX handles use wp_ajax_no_priv while private handles use
	 * wp_ajax.
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_public() {
		return false;
	}

	/**
	 * The name of the nonce that must be passed with this ajax handler.
	 * Defaults to use the name of the action combined with a suffix.
	 *
	 * @access public
	 * @return string
	 */
	public function get_nonce_name() {
		return $this->get_action() . '_nonce';
	}

	/**
	 * Registers an action with WordPress. The public or private action
	 * name version will be used based on the result of `is_public`.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {
		add_action(
			$this->get_action_to_register(),
			array( $this, 'handle_ajax' )
		);
	}

	/**
	 * Abstract method to be used to provide concrete implementation of
	 * an AJAX handler.
	 *
	 * You can return a value here like a regular function and it will
	 * be encoded to JSON and sent to the client.
	 *
	 * Else you can manually send success or errors to the client with
	 * the helpers, `send_success` and `send_json`.
	 *
	 * Any exceptions in this method are caught automatically and send
	 * as errors in JSON format to the client.
	 *
	 * @access public
	 * @abstract
	 * @return mixed
	 */
	public function run( $params ) {

	}

	/**
	 * The callback wired to the corresponding WordPress ajax action. It
	 * calls and wraps the response from run and sends success or error
	 * responses in JSON format to the client.
	 *
	 * @access public
	 * @return void
	 */
	public function handle_ajax() {
		try {
			// ensure that the current user is allowed to call this
			// ajax handler
			$this->authorize();

			// execute concrete handler and capture response
			$result = $this->run( $this->get_params() );

			// if json was not sent manually send response as json
			if ( ! $this->did_send_json ) {
				// for no result returned, the default response data sent to the client
				// is 'true' to indicate success.
				if ( is_null( $result ) ) {
					$result = true;
				}
				$this->send_success( $result );
			} else {
				// json was already sent, just quit
				// only for PHPUnit since send_success already
				// quits
				$this->quit();
			}
		} catch ( \Exception $e ) {
			// caught an exception, so send it as JSON
			$this->send_error( $e->getMessage(), 500 );
		}
	}

	/* helpers */
	/**
	 * Checks if the ajax handler was called with a valid nonce. Exits
	 * early if the nonce is not valid.
	 *
	 * Will return false if running under PHPUnit.
	 *
	 * @return boolean
	 */
	public function authorize() {
		$nonce = $this->get_nonce_value();

		if ( wp_verify_nonce( $nonce, $this->get_action() ) !== false ) {
			return $this->has_permissions();
		} else {
			$this->send_error( 'invalid_nonce' );
			return false;
		}
	}

	/**
	 * Checks if the current user has permissions to access this ajax
	 * handler.
	 *
	 * For public ajax handlers, permission check is skipped.
	 *
	 * If not enough permissions, exits early.
	 *
	 * @access public
	 * @return boolean
	 */
	public function has_permissions() {
		if ( ! $this->is_public() ) {
			$valid_perms = is_user_logged_in() && $this->has_capabilities();

			if ( ! $valid_perms ) {
				$this->send_error( 'invalid_permissions' );
			}

			return $valid_perms;
		} else {
			return true;
		}
	}

	/**
	 * Checks if the current user role has enough permissions to access
	 * this ajax handler.
	 *
	 * Exits early if not enough capabilities were found.
	 *
	 * By default it checks for the capability, manage_options. You
	 * should override this to check for capabilities specific to your
	 * ajax handler.
	 *
	 * @access public
	 * @return void
	 */
	public function has_capabilities() {
		$valid_caps = current_user_can( 'manage_options' );

		if ( ! $valid_caps ) {
			$this->send_error( 'invalid_capabilities' );
		}

		return $valid_caps;
	}

	/**
	 * Calculates the action name to register with WordPress based on
	 * whether the handler is for public access or admin only.
	 *
	 * @access public
	 * @return string
	 */
	public function get_action_to_register() {
		if ( ! $this->is_public() ) {
			return 'wp_ajax_' . $this->get_action();
		} else {
			return 'wp_ajax_no_priv_' . $this->get_action();
		}
	}

	/**
	 * Lookups the value of the nonce field for the current ajax
	 * handler.
	 *
	 * @access public
	 * @return string
	 */
	public function get_nonce_value() {
		$key = $this->get_nonce_name();

		if ( array_key_exists( $key, $_GET ) ) {
			return $_GET[ $key ];
		} elseif ( array_key_exists( $key, $_POST ) ) {
			return $_POST[ $key ];
		} else {
			return '';
		}
	}

	/**
	 * Sends a success JSON response to the client and exits.
	 *
	 *
	 * @access public
	 * @param mixed $data The data to send to the client.
	 * @return void
	 */
	public function send_success( $data, $status = 200 ) {
		$response = array(
			'status' => 'success',
			'data'   => $data,
		);

		$this->send_header( 'Status', $status );
		$this->send_json( $response );
	}

	/**
	 * Sends an error JSON response to the client and exits.
	 *
	 * @access public
	 * @param mixed $data The error code/message to send to the client
	 * @return void
	 */
	public function send_error( $error, $status = 403 ) {
		$response = array(
			'status' => 'error',
			'data'   => $error,
		);

		$this->send_header( 'Status', 403 );
		$this->send_json( $response );
	}

	/**
	 * Sends JSON to the client and exits.
	 *
	 * The JSON is optionally gzipped if the required PHP extensions are present.
	 *
	 * @access public
	 * @param mixed $data The data to send to the client
	 * @return void
	 */
	public function send_json( $data ) {
		$this->send_header( 'Content-Type', 'application/json' );
		$started = ob_start( 'ob_gzhandler' );

		if ( ! $started ) {
			ob_start();
		}

		echo json_encode( $data );

		$this->did_send_json = true;
		$this->quit();
	}

	/**
	 * Sends an HTTP header to the client.
	 *
	 * No headers will be output when running under PHPUnit.
	 *
	 * @access public
	 * @param string $name The name of the header to send
	 * @param string $value The value of the header to send
	 * @return void
	 */
	public function send_header( $name, $value ) {
		if ( ! defined( 'PHPUNIT_RUNNER' ) ) {
			if ( $name === 'Status' ) {
				http_response_code( $value );
			} else {
				header( "$name: $value" );
			}
		}
	}

	/**
	 * Returns the params passed to the AJAX handler in POST in JSON
	 * format. The sent json is converted into a PHP array.
	 *
	 * An empty array is returned if no json was sent.
	 *
	 * The client must stringify and send the JSON in the parameter
	 * named, 'action_data'.
	 *
	 * @access public
	 * @return array
	 */
	public function get_params() {
		if ( array_key_exists( 'action_data', $_POST ) ) {
			$json = wp_unslash( $_POST['action_data'] );
			$data = json_decode( $json, true );

			if ( ! is_array( $data ) ) {
				throw new \Exception( 'invalid_params' );
			}

			return $data;
		} else {
			return array();
		}
	}

	/**
	 * Exits execution immediately. Does not exit when
	 * running under PHPUnit.
	 *
	 * @access public
	 * @return void
	 */
	public function quit() {
		if ( ! defined( 'PHPUNIT_RUNNER' ) ) {
			die();
		}
	}

}
