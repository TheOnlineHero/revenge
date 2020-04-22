<?php
/*
Plugin Name: Revenge
Plugin URI: http://wordpress.org/extend/plugins/revenge/
Description: Take revenge against your attackers. With this plugin, they will be shutdown.

Installation:

1) Install WordPress 5.5 or higher

2) Download the latest from:

http://wordpress.org/extend/plugins/revenge

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

function revenge_activate() {
  global $wpdb;
  $table_name = $wpdb->prefix . "revenge";
  $checktable = $wpdb->query("SHOW TABLES LIKE '$table_name'");
  if ($checktable == 0) {
    $sql = "CREATE TABLE $table_name (
    threat VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL
    );";
    $wpdb->query($sql); 
  }
}
register_activation_hook( __FILE__, 'revenge_activate' );


add_action('admin_menu', 'register_revenge_page');
function register_revenge_page() {
   add_menu_page('Revenge', 'Revenge', 'manage_options', 'revenge/revenge_stats.php', '',  '', 180);
}

add_action("admin_init", "revenge_register_style_scripts");
function revenge_register_style_scripts() {
  wp_register_style("revenge", plugins_url("/css/style.css", __FILE__));
  wp_enqueue_style("revenge");
}

add_action( 'init', 'register_revenge' );
function register_revenge() {
	$bomb = false;
	global $wp;
	$currentURL = add_query_arg( $wp->query_vars, home_url() );

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (
			preg_match("/nikto|sqlmap|ripper|aircrack|rainbow|cain|crack|hashcat|saminside|hydra|nutch|larbin|heritrix|archiver|baiduspider|baidu|bin\/bash|dnyzbot|dotbot|eval\(|go-http-client|nimbostratus|python-requests|scrapy|seznambot|sogou|spbot|uptimebot|miniredir|winhttprequest|zmeu/",$agent)
		)
		{
			$bomb = true;
			insertRevengeData('bot attack');
		}
	}


	if (
		(
			(isset($_GET['author_name']) && $_GET['author_name'] != '') ||
			(isset($_GET['author']) && $_GET['author'] != '')
		) && (!preg_match("/wp-admin\/export/", $currentURL))) {
		$bomb = true;
		insertRevengeData('author query vulnerability');
	}

	if (preg_match("/\/wp-config|\/phpmyadmin|\/phpunit|\<\?php|passwd|\/dfs\/|\/autodiscover\/|\/wpad\.|webconfig\.txt|vuln\.|base64|%3Cscript|\<script/", $currentURL)) {
		$bomb = true;
		if (preg_match("/\/wp-config/", $currentURL)) {
			insertRevengeData('wp-config file targeted');
		} else if (preg_match("/\/phpmyadmin/", $currentURL)) {
			insertRevengeData('phpmyadmin targeted');
		} else if (preg_match("/\/phpunit/", $currentURL)) {
			insertRevengeData('phpunit targeted');
		} else if (preg_match("/\<\?php/", $currentURL)) {
			insertRevengeData('php code injection');
		} else if (preg_match("/passwd/", $currentURL)) {
			insertRevengeData('password injection');
		} else if (preg_match("/\/dfs\//", $currentURL)) {
			insertRevengeData('dfs targeted');
		} else if (preg_match("/\/autodiscover/", $currentURL)) {
			insertRevengeData('autodiscover targeted');
		} else if (preg_match("/\/wpad\./", $currentURL)) {
			insertRevengeData('wpad targeted');
		} else if (preg_match("/webconfig\.txt/", $currentURL)) {
			insertRevengeData('webconfig targeted');
		} else if (preg_match("/vuln\./", $currentURL)) {
			insertRevengeData('vuln targeted');
		} else if (preg_match("/base64/", $currentURL)) {
			insertRevengeData('base64 script injection');
		} else if (preg_match("/script/", $currentURL)) {
			insertRevengeData('script injection');
		}
	}

	if ($bomb) {
		revengeSendBomb();
		exit();
	}
}

function insertRevengeData($threat) {
	global $wpdb;
	$wpdb->insert($wpdb->prefix . "revenge", array(
		"threat" => $threat,
		"created_at" => gmdate("Y-m-d H:i:s")
	));
}

function revengeSendBomb(){
	ob_start();
	ob_implicit_flush(0);
	$HTTP_ACCEPT_ENCODING = $_SERVER['HTTP_ACCEPT_ENCODING'];
	if (headers_sent()){
		$encoding = false;
	} elseif (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ){
		$encoding = 'x-gzip';
	} elseif (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ){
		$encoding = 'gzip';
	} else {
		$encoding = false;
	}

	if ($encoding){

		for ($k=0;$k<1000;$k++) {
			for ($z=0;$z<1000;$z++) {
				for ($i=0;$i<1000;$i++) {
					echo "<h1>Links</h1><h2>ZIP ZIP</h2><h2>Stop Hacking Businesses</h2><p>Be prepared to download 4GB of data in one hit.</p>";	
				}
				for ($i=0;$i<1000;$i++) {
	    		echo "<h2>This will gobble your resources, Gobble Gobble!!!</h2>";
				}
				for ($i=0;$i<1000;$i++) {
					echo "<h3>You enjoy hacking sites, no worries, this plugin will destroy you guys. Perfect!!!</h3>";	
				}
				echo "<ul>";
				for ($i=0;$i<1000;$i++) {
					echo "<li>This is a link, you should follow this link. This link is da bomb. This is not a joke. You should follow and follow.</li>";
				}
				echo "</ul>";
				echo "<dt>";
				for ($i=0;$i<1000;$i++) {
					echo "<dt>Link Profile</dt>";
					echo "<dd>This is a link, you should follow this link. This link is da bomb. This is not a joke. You should follow and follow.</dd>";
				}
				echo "</dt>";
				echo "<ul>";
				for ($i=0;$i<1000;$i++) {
					echo "<li>This is a link, you should follow this link. This link is da bomb. This is not a joke. You should follow and follow.</li>";
				}
				echo "</ul>";
				echo "<dt>";
				for ($i=0;$i<1000;$i++) {
					echo "<dt>Link Profile</dt>";
					echo "<dd>This is a link, you should follow this link. This link is da bomb. This is not a joke. You should follow and follow.</dd>";
				}
				echo "</dt>";
			}
		}

		$contents = ob_get_contents();
		ob_end_clean();
		header('Content-Encoding: '.$encoding);
		print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
		$size = strlen($contents);
		$contents = gzcompress($contents, 9);
		$contents = substr($contents, 0, $size);
		print($contents);
	} else {
		ob_end_flush();
	}
  exit;
}