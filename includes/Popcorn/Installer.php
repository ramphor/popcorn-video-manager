<?php
namespace Ramphor\Popcorn;

class Installer
{
    protected static function createVideoTable()
    {
        /**
         * [$wpdb description]
         *
         * @var \wpdb
         */
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}popcorn_videos` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `video_id` BIGINT NOT NULL,
            `duration` INT NOT NULL,
            `embed_code` TEXT NOT NULL,
            `preview_url` TEXT NOT NULL,
            `updated_on` DATETIME DEFAULT NOW(),
            PRIMARY KEY (`id`)
        )";

        return $wpdb->query($sql);
    }

    protected static function createRatingTable()
    {
        /**
         * [$wpdb description]
         *
         * @var \wpdb
         */
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}popcorn_ratings` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `post_id` BIGINT NOT NULL,
            `stars` FLOAT NOT NULL DEFAULT 0,
            `user_id` BIGINT NOT NULL DEFAULT 0,
            `comment` TEXT,
            `user_ip` TEXT NOT NULL,
            `created_at` DATETIME DEFAULT NOW(),
            PRIMARY KEY (`id`)
        )";

        return $wpdb->query($sql);
    }

    public static function active()
    {
        static::createVideoTable();
        static::createRatingTable();
    }
}
