<?php

class sca_newsletter {

	function __construct() {
		$this->url = get_latest_sca_file();
		$this->error = '';
		$this->err_cnt = 0;
		$this->filename = '';
		$this->temp_file = '';
		$this->is_new = 0;
		$this->attachment_id = 0;
	}

	function is_newsletter_new() {
		$old_url = get_option('sca_newsletter_url','');
		$old_id = get_option('sca_newsletter_id',0);
		$old_month = get_option('sca_newsletter_month');
		if ($old_url == $this->url) {
			return(false);
		} else {
			$this->is_new = 1;
			return(true);
		}
	}

	function pull_newsletter() {
		$month = date('n');
		$day = date('j');
		$year = date('Y');
		if ($day > 10) {
			$month++;
		}
		if ($month > 12) {
			$year++;
			$month = 1;
		}
		$this->month = $month;
		$temp_filename = "SCA_Acorn_". date('M-Y',mktime(0, 0, 0, $month, 1, $year)) . '.pdf';
		$this->temp_file = SCA_NEWSLETTER_TMP . "/" . $temp_filename;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$fp = fopen($this->temp_file, 'w');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		if (curl_exec($ch) === false) {
			print "Cannot download $url";
			$this->error = "Cannot download $url";
			$this->err_cnt = 1;
		}
		curl_close ($ch);
		fclose($fp);
		if ($this->err_cnt == 1) {
			return(false);
		} else {
			return(true);
		}
	}

	function upload_file() {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		$file = array(
			'name'     => basename($this->temp_file),
			'type'     => mime_content_type( $this->temp_file ),
			'tmp_name' => $this->temp_file,
			'size'     => filesize( $this->temp_file ),
		);
		$sideload = wp_handle_sideload(
			$file,
			array(
				'test_form'   => false // no needs to check 'action' parameter
			)
		);

		if( ! empty( $sideload[ 'error' ] ) ) {
			$this->error = $sideload [ 'error' ];
			$this->err_cnt = 1;
			return false;
		}

		$attachment_id = wp_insert_attachment(
			array(
				'guid'           => $sideload[ 'url' ],
				'post_mime_type' => $sideload[ 'type' ],
				'post_title'     => basename( $sideload[ 'file' ] ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$sideload[ 'file' ]
		);

		if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			$this->error = "Unable to insert the attachment to WP";
			$this->err_cnt = 1;
			return false;
		}

		// update medatata, regenerate image sizes
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		wp_update_attachment_metadata(
			$attachment_id,
			wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
		);
		$this->attachment_id = $attachment_id;
		return $attachment_id;
		
	}

	function update_newsletter() {
		update_option('sca_newsletter_url',$this->url);
		update_option('sca_newsletter_id', $this->attachment_id);
		update_option('sca_newsletter_month', $this->month);
		// Add Logging
	}

}

