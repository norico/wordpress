<?php
/*
 * Plugin name: Intranet
 * Description: Intranet plugin for WordPress
 * Version: 0.0.1
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require __DIR__.('vendor/autoload.php');
$intranet = new Intranet(__FILE__);
$intranet->run();