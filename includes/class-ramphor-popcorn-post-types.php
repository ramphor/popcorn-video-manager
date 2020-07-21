<?php
class Ramphor_Popcorn_Post_Types {
	public function __construct() {

		add_action( 'init', array( $this, 'register_post_types' ) );
	}

	public function register_post_types() {
		$platform_type = get_option( 'ramphor_popcorn_source_type', 'video' );
		$video_labels  = array();
		if ( $platform_type === 'movie' ) {
			$video_labels = $video_labels + array(
				'name' => __( 'Movies', 'ramphor_popcorn' ),
			);
		} else {
			$video_labels = $video_labels + array(
				'name' => __( 'Videos', 'ramphor_popcorn' ),
			);
		}

		$video_post_type_args = array(
			'labels'       => $video_labels,
			'public'       => true,
			'hierarchical' => true,
			'show_ui'      => true,
			'supports'     => array( 'title', 'editor', 'comments', 'author', 'excerpt', 'thumbnail' ),
		);

		register_post_type(
			'video',
			apply_filters( 'ramphor_video_post_type_args', $video_post_type_args )
		);
	}
}

new Ramphor_Popcorn_Post_Types();
