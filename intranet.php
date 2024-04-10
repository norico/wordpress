<?php
/*
 * Plugin name:         Intranet
 * Description:         Intranet plugin for WordPress
 * Version:             1.0.2
 * Text Domain:         intranet
 * Domain Path:         /languages
 * Requires PHP:        7.4
 * Requires at least:   5.6
 * Network:             true
 * Author:              Norico
 * Author URI:          https://github.com/norico/intranet/
 * License:             GPL-2.0-or-later
 * GitHub Plugin URI:   https://github.com/norico/intranet/
 * GitHub Branch:       main
 *
 * @package             intranet
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once __DIR__. '/vendor/autoload.php';
$intranet = new Intranet\Intranet(__FILE__);
$intranet->run();