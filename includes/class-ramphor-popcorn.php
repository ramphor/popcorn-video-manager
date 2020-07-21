<?php

class Ramphor_Popcorn {
	protected static $instance;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			sefl::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
        $this->includes();
    }

    public function includes() {
    }
}
