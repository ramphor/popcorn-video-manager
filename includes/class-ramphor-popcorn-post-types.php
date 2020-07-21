<?php
class Ramphor_Popcorn_Post_Types {
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	public function register_post_types() {
		$platform_type = get_option( 'ramphor_popcorn_source_type', 'video' );
		$video_labels  = array();
		if ( $platform_type === 'movie' ) {
			$video_labels = $video_labels + array(
				'name'          => __( 'Movies', 'ramphor_popcorn' ),
				'singular_name' => __( 'Movie', 'ramphor_popcorn' ),
				'all_items'     => __( 'All Moviess', 'ramphor_popcorn' ),
				'add_new'       => __( 'Add new', 'ramphor_popcorn' ),
			);
		} else {
			$video_labels = $video_labels + array(
				'name'          => __( 'Videos', 'ramphor_popcorn' ),
				'singular_name' => __( 'Video', 'ramphor_popcorn' ),
				'all_items'     => __( 'All Videos', 'ramphor_popcorn' ),
				'add_new'       => __( 'Add new', 'ramphor_popcorn' ),
			);
		}

		$video_post_type_args = array(
			'labels'          => $video_labels,
			'public'          => true,
			'hierarchical'    => true,
			'show_ui'         => true,
			'capability_type' => 'post',
			'show_in_rest'    => true,
			'supports'        => array( 'title', 'editor', 'comments', 'author', 'excerpt', 'thumbnail' ),
		);

		register_post_type(
			'video',
			apply_filters(
				'ramphor_video_post_type_args',
				$video_post_type_args,
				$platform_type
			)
		);
	}

	public function register_taxonomies() {
		$category_labels = array(
			'name'        => __( 'Video Categories', 'ramphor_popcorn' ),
			'plural_name' => __( 'Video Category', 'ramphor_popcorn' ),
		);
		$video_cat_args  = array(
			'lables'       => $category_labels,
			'public'       => true,
			'hierarchical' => true,
		);
		register_taxonomy( 'video_cat', 'video', apply_filters( 'ramphor_popcorn_video_cat_args', $video_cat_args ) );

		$tag_labels     = array(
			'name'        => __( 'Tags', 'ramphor_popcorn' ),
			'plural_name' => __( 'Tag', 'ramphor_popcorn' ),
		);
		$video_tag_args = array(
			'labels'       => $tag_labels,
			'public'       => true,
			'hierarchical' => false,
		);
		register_taxonomy( 'video_tag', 'video', apply_filters( 'ramphor_popcorn_video_tag_args', $video_tag_args ) );
	}
}

new Ramphor_Popcorn_Post_Types();
