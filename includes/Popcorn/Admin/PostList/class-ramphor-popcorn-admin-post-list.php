<?php

class Ramphor_Popcorn_Admin_Post_List
{
    public function __construct()
    {
        add_action('admin_init', array( $this, 'customize_post_list' ));
    }

    public function customize_post_list()
    {
        add_filter(
            sprintf('manage_edit-%s_columns', Ramphor_Popcorn::POST_TYPE),
            array( $this, 'add_columns' )
        );
        add_filter(
            sprintf('manage_%s_posts_custom_column', Ramphor_Popcorn::POST_TYPE),
            array( $this, 'show_columns' ),
            10,
            2
        );
    }

    public function add_columns($columns)
    {
        return array_merge(
            array_splice($columns, 0, 1),
            array( 'thumb' => __('Thumbnail', 'ramphor_popcorn') ),
            $columns
        );
    }

    public function show_columns($colname, $video_id)
    {
        if ($colname === 'thumb') {
            if (has_post_thumbnail($video_id)) {
                echo wp_get_attachment_image(
                    get_post_thumbnail_id($video_id),
                    array( 140, 60 )
                );
            } else {
                echo '<div class="ramphor-popcorn-image-holder">
                    <span class="dashicons dashicons-format-image"></span>
                </div>';
            }
        }
    }
}

new Ramphor_Popcorn_Admin_Post_List();
