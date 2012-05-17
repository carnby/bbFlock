<?php
/*
Plugin Name: bbPM
Description: Adds the ability for users of a forum to send private messages to each other.
Version: 1.0.0
Author: Eduardo Graells, based on bbPM by Ben L.
Author URI: http://about.me/egraells
Text Domain: bbpm
Domain Path: /translations
*/

/**
 * @package bbPM
 * @version 1.0.1
 * @author Nightgunner5
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License, Version 3 or higher
 */

load_plugin_textdomain( 'bbpm', dirname( __FILE__ ) . '/translations' );

bb_register_activation_hook(__FILE__, 'bbpm_install');

function bbpm_install() {
    global $bbdb;
	$bbdb->bbpm = $bbdb->prefix . 'bbpm';
	$bbdb->bbpm_meta = $bbdb->prefix . 'bbpm_meta';
	
    $queries = array();
    $queries['bbpm_meta'] = "CREATE TABLE {$bbdb->bbpm_meta} (
      `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
      `bbpm_id` bigint(20) NOT NULL DEFAULT '0',
      `meta_key` varchar(255) DEFAULT NULL,
      `meta_value` longtext,
      PRIMARY KEY (`meta_id`),
      KEY `bbpm_id` (`bbpm_id`),
      KEY `meta_key` (`meta_key`)
    );";
    
    $queries['bbpm'] = "CREATE TABLE {$bbdb->bbpm} (
        `ID` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `pm_thread` BIGINT UNSIGNED NOT NULL,
        `pm_from` BIGINT UNSIGNED NOT NULL,
        `pm_text` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `sent_on` INT( 10 ) NOT NULL,
        `reply_to` BIGINT UNSIGNED DEFAULT NULL,
        KEY ( `pm_from` ),
        KEY ( `reply_to` ),
        KEY ( `pm_thread` )
    );";
    
    require_once(BBPATH . 'bb-admin/upgrade-functions.php');
	bb_dbDelta($queries);
}

$bbpm_dir = dirname(__FILE__);

require_once($bbpm_dir . '/functions.bbpm.php');
require_once($bbpm_dir . '/class.bbpm-message.php');
require_once($bbpm_dir . '/class.bbpm.php');
require_once($bbpm_dir . '/template-tags.php');
require_once($bbpm_dir . '/admin.php');

/**
 * The bbPM object
 *
 * @global bbPM $GLOBALS['bbpm']
 * @name $bbpm
 * @since 0.1-alpha1
 */
$GLOBALS['bbpm'] = new bbPM;

/**
 * @since 0.1-alpha1
 * @return bool true if the current page is the private messaging page, false otherwise.
 */
function is_pm() {
	return substr( ltrim( substr( $_SERVER['REQUEST_URI'] . '/', strlen( bb_get_option( 'path' ) ) ), '/' ), 0, 3 ) == 'pm/';
}

function bbpm_load($not_used = '') {

    if (!is_front() or !isset($_GET['pm']))
        return;
        
    add_filter('bb_page_header_override', 'bbpm_override_page_header');
        
    add_filter('bb_header_breadcrumb', 'bbpm_breadcrumb');
    add_filter('bb_header_breadcrumb_override', 'bbpm_override_page_header');
        
    global $bbpm, $bbpm_dir;
        
    $pm_param = empty($_GET['pm']) ? 'viewall' : $_GET['pm'];
        
    if (!bb_get_option('mod_rewrite')) {
	    $_SERVER['REQUEST_URI'] = bb_get_option('path') . rtrim('pm/' . $pm_param, '/');
	    if (isset($_GET['page'])) {
	        $page = intval($_GET['page']);
	        if ($page > 1)
	            $_SERVER['REQUEST_URI'] .= '/page/' . $page;
	    }
    }
    
    require($bbpm_dir . '/privatemessages.php');
    exit;
}

function bbpm_override_page_header($override) {
    return true;
}

//add_action('bb_index.php_pre_db', 'bbpm_load');

add_action('bb_admin_menu_generator', 'bbpm_configure_admin');

function bbpm_configure_admin() {
	bb_admin_add_submenu('bbPM', 'use_keys', 'bbpm_admin_page', 'options-general.php' );
}





