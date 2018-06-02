<?php
/**
 * Elgg Gifts plugin
 * Send gifts to you friends
 *
 * @package Gifts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Christian Heckelmann
 * @copyright Christian Heckelmann
 * @link http://www.heckelmann.info
 *
 * updated by iionly (iionly@gmx.de)
 */

require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

elgg_register_event_handler('init', 'system', 'gifts_init');

/**
 * Initialize Plugin
 */
function gifts_init() {

	// Extend CSS
	elgg_extend_view('css/elgg', 'gifts/css');

	// Show in Menu
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('site', [
			'name' => 'gifts',
			'text' => elgg_echo('gifts:menu'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/index",
		]);
		elgg_register_menu_item('page', [
			'name' => '01_yourgifts',
			'text' => elgg_echo('gifts:yourgifts'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/index",
			'context' => 'gifts',
			'section' => 'default',
		]);
		elgg_register_menu_item('page', [
			'name' => '03_sentgifts',
			'text' => elgg_echo('gifts:sent'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/sent",
			'context' => 'gifts',
			'section' => 'default',
		]);
	}

	// Show all gifts?
	$showallgifts = (string) elgg_get_plugin_setting('showallgifts', 'gifts');
	if ($showallgifts == '1') {
		elgg_register_menu_item('page', [
			'name' => '02_allgifts',
			'text' => elgg_echo('gifts:allgifts'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/all",
			'context' => 'gifts',
			'section' => 'default',
		]);
	}

	// Add Widget
	elgg_register_widget_type('gifts', elgg_echo("gifts:widget"), elgg_echo("gifts:widget:description"));
	if ($showallgifts == '1') {
		elgg_register_widget_type('index_gifts', elgg_echo("gifts:widget"), elgg_echo("gifts:index_widget:description"), ['index']);
	}
	// Register title urls for gifts index widget
	elgg_register_plugin_hook_handler('entity:url', 'object', "gifts_widget_urls");

	// Pagehandler
	elgg_register_page_handler('gifts', 'gifts_page_handler');
	
	// Override the default url to view a gift object
    elgg_register_plugin_hook_handler('entity:url', 'object', 'gifts_url');

	// Override the default url of a giftsfile icon
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'giftsfile_url');

	// Extend avatar hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'gifts_user_hover_menu');

	// Entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'gifts_entity_menu_setup');

	// Register for search (or rather for activity page filter only as gifts won't appear in search results due to title attribute not used)
	elgg_register_entity_type('object', Gifts::SUBTYPE);

	// Register actions
	elgg_register_action('gifts/savegifts', dirname(__FILE__) . '/actions/savegifts.php', 'admin');
	elgg_register_action('gifts/upgrade', dirname(__FILE__) . '/actions/upgrade.php', 'admin');
	elgg_register_action('gifts/sendgift', dirname(__FILE__) . '/actions/send.php', 'logged_in');
	elgg_register_action('gifts/delete', dirname(__FILE__) . '/actions/delete.php', 'logged_in');
	elgg_register_action('gifts/ajaxGetPoints', dirname(__FILE__) . '/actions/ajaxGetPoints.php', 'logged_in');
	elgg_register_action('gifts/ajaxImage', dirname(__FILE__) . '/actions/ajaxImage.php', 'logged_in');

	// Override permissions for gift objects to allow for deleting them both by sender and receiver
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'gifts_permissions_check');
}

/**
 * Page Handler
 */
function gifts_page_handler($page) {
	$resource_vars = [];
	if (isset($page[0])) {
		$resource_vars['username'] = $page[0];
	} else {
		return false;
	}
	if (isset($page[1])) {
		switch($page[1]) {
			case "read":
			case "index":
				echo elgg_view_resource('gifts/index', $resource_vars);
				break;
			case "sent":
				elgg_gatekeeper();
				echo elgg_view_resource('gifts/sent', $resource_vars);
				break;
			case "sendgift":
				elgg_gatekeeper();
				$resource_vars['send_to'] = (int) get_input('send_to');
				echo elgg_view_resource('gifts/sendgift', $resource_vars);
				break;
			case "singlegift":
				$resource_vars['guid'] = (int) get_input('guid');
				echo elgg_view_resource('gifts/singlegift', $resource_vars);
				break;
			case "all":
				echo elgg_view_resource('gifts/all', $resource_vars);
				break;
			default:
				return false;
		}
	} else {
		echo elgg_view_resource('gifts/index', $resource_vars);
	}

	return true;
}
