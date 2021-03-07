<?php
class Ramphor_Popcorn_Admin_Menus
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'change_video_menu_labels' ));
    }

    public function change_video_menu_labels()
    {
        global $menu;

        foreach ($menu as $index => $m) {
            if (array_search('edit.php?post_type=video', $m) !== false) {
                $menu[ $index ][0] = sprintf(__('%s Manager', 'ramphor_popcorn'), $menu[ $index ][0]);
            }
        }
    }
}

new Ramphor_Popcorn_Admin_Menus();
