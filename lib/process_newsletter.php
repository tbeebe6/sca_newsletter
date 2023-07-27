<?php

function sca_check_newsletter() {
	$sca = new sca_newsletter();
	if ($sca->is_newsletter_new()) {
		if ($sca->pull_newsletter()) {
			if ($sca->upload_file()) {
				$sca->update_newsletter();
			}
		}
	}
}
		
