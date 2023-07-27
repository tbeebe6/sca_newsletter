<?php

include '../../../wp-config.php';
#$url = get_latest_sca_file();
#print "Url: $url\n";
sca_check_newsletter();
update_option('sca_newsletter_url','');
update_option('sca_newsletter_id',0);



