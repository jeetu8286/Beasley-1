<?php

class DFP_ACM_Provider extends \ACM_Provider {

	public $crawler_user_agent = 'Mediapartners-Google';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->ad_code_args = array(
			array(
				'key'       => 'site_name',
				'label'     => 'Site Name',
				'editable'  => true,
				'required'  => true,
			),
			array(
				'key'       => 'zone1',
				'label'     => 'zone1',
				'editable'  => true,
				'required'  => true,
			),
		);

		parent::__construct();
	}

}