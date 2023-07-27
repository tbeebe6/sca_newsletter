<?php

# Run the check daily for the new newsletter
register_activation_hook( __FILE__, 'sca_newsletter_activation' );

add_action( 'sca_daily_event', 'sca_check_newsletter' );

function sca_newsletter_activation() {
print "Scheduled\n";
    wp_schedule_event( time(), 'daily', 'sca_daily_event' );
}

register_deactivation_hook( __FILE__, 'sca_newsletter_deactivation' );

function sca_newsletter_deactivation() {
	print "REMOVED";
    wp_clear_scheduled_hook( 'sca_daily_event' );
}
