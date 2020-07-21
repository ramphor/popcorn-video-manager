<?php

class Ramphor_Popcorn {
	protected static $instance;
	protected $composer_loaded = false;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->load_composer();
		$this->includes();
	}

	public function load_composer() {
		$composer_autoloader = sprintf( '%s/vendor/autoload.php', dirname( POPCORN_PLUGIN_FILE ) );
		if ( file_exists( $composer_autoloader ) ) {
			require_once $composer_autoloader;

			// Create flag to load other features
			$this->composer_loaded = true;
		}
	}

	public function includes() {
		if ( ! $this->composer_loaded ) {
			return;
		}
		require_once dirname( __FILE__ ) . '/class-ramphor-popcorn-post-types.php';
	}
}
