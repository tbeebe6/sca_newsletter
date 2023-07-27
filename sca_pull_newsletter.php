<?php

/**
 * @package SCA Pull Newsletter
 * @version 1.0
 */
/*
Plugin Name: SCA Pull Newsletter
Plugin URI: http://atlantia.sca.org/
Description: This will check for and pull the latest copy of the newsletter based on the alt img tag on the sca corporate site
Author: Thomas Beebe
Version: 1
*/

require_once(WP_PLUGIN_DIR .'/sca_pull_newsletter/config/config.php');
require_once(WP_PLUGIN_DIR .'/sca_pull_newsletter/classes/sca_newsletter.php');
require_once(WP_PLUGIN_DIR .'/sca_pull_newsletter/lib/get_latest_sca.php');
require_once(WP_PLUGIN_DIR .'/sca_pull_newsletter/lib/process_newsletter.php');
require_once(WP_PLUGIN_DIR .'/sca_pull_newsletter/lib/sca_shortcode.php');


# Run the check daily for the new newsletter
register_activation_hook( __FILE__, 'sca_newsletter_activation' );

add_action( 'sca_daily_event', 'sca_check_newsletter' );

function sca_newsletter_activation() {
    wp_schedule_event( time(), 'daily', 'sca_daily_event' );
}

register_deactivation_hook( __FILE__, 'sca_newsletter_deactivation' );

function sca_newsletter_deactivation() {
    wp_clear_scheduled_hook( 'sca_daily_event' );
}

