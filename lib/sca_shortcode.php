<?php


function sca_get_newsletter_shortcode($atts){
	$attr = shortcode_atts(
		array(
			'text' => 'The Acorn – Atlantia’s Newsletter',
		), $atts );
	$text = $attr['text'];
	$attachment_id = get_option('sca_newsletter_id');
	$newsletter_url = wp_get_attachment_url($attachment_id);
	if (!$newsletter_url) {
		$newsletter_url = 'https://members.sca.org/apps/#NewsletterFiles/6';
	}
	$text = "<a href='". $newsletter_url ."'><strong>". $text ."</strong></a>\n";
	return($text);
}

add_shortcode('sca_latest_newsletter', 'sca_get_newsletter_shortcode');


