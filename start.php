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

elgg_register_event_handler('init', 'system', 'gifts_init');

/**
 * Initialize Plugin
 */
function gifts_init() {

	elgg_extend_view('css/elgg','gifts/css');

	// Show in Menu
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('site', array(
			'name' => 'gifts',
			'text' => elgg_echo('gifts:menu'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/index"
		));
	}

	elgg_register_admin_menu_item('administer', 'gifts', 'administer_utilities');

	elgg_register_menu_item('page', array(
		'name' => 'yourgifts',
		'text' => elgg_echo('gifts:yourgifts'),
		'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/index",
		'context' => 'gifts',
		'section' => 'default'
	));

	// Show all gifts?
	if (elgg_get_plugin_setting('showallgifts', 'gifts') == 1) {
		elgg_register_menu_item('page', array(
			'name' => 'allgifts',
			'text' => elgg_echo('gifts:allgifts'),
			'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/all",
			'context' => 'gifts',
			'section' => 'default'
		));
	}

	elgg_register_menu_item('page', array(
		'name' => 'sentgifts',
		'text' => elgg_echo('gifts:sent'),
		'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/sent",
		'context' => 'gifts',
		'section' => 'default'
	));
	elgg_register_menu_item('page', array(
		'name' => 'sendgifts',
		'text' => elgg_echo('gifts:sendgifts'),
		'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/sendgift",
		'context' => 'gifts',
		'section' => 'default'
	));

	// Add Widget
	elgg_register_widget_type('gifts', elgg_echo("gifts:widget"), elgg_echo("gifts:widget:description"));
	if (elgg_get_plugin_setting('showallgifts', 'gifts') == 1) {
		elgg_register_widget_type('index_gifts', elgg_echo("gifts:widget"), elgg_echo("gifts:index_widget:description"), array("index"));
		//register title urls for gifts index widget
		elgg_register_plugin_hook_handler('entity:url', 'object', "gifts_widget_urls");
	}

	elgg_register_page_handler('gifts', 'gifts_page_handler');
	// override the default url to view a gift object
    elgg_register_plugin_hook_handler('entity:url', 'object', 'gifts_url');

	// Extend avatar hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'gifts_user_hover_menu');

	// Register actions
	$base_dir = elgg_get_plugins_path() . 'gifts/actions';
	elgg_register_action("gifts/settings", "$base_dir/savesettings.php", 'admin');
	elgg_register_action("gifts/savegifts", "$base_dir/savegifts.php", 'admin');
	elgg_register_action("gifts/sendgift", "$base_dir/send.php", 'logged_in');
	elgg_register_action("gifts/delete", "$base_dir/delete.php", 'logged_in');
	elgg_register_action("gifts/ajaxGetPoints", "$base_dir/ajaxGetPoints.php", 'logged_in');
	elgg_register_action("gifts/ajaxImage", "$base_dir/ajaxImage.php", 'logged_in');

	// override permissions for gift objects to allow for deleting them both by sender and receiver
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'gifts_permissions_check');
}

/**
 * Page Handler
 */
function gifts_page_handler($page) {
	$resource_vars = array();
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
				echo elgg_view_resource('gifts/sent', $resource_vars);
				break;
			case "sendgift":
				echo elgg_view_resource('gifts/sendgift', $resource_vars);
				break;
			case "singlegift":
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

/**
 * URL Handler
 */
function gifts_url($hook, $type, $url, $params) {
	$entity = $params['entity'];

	if (!elgg_instanceof($entity, 'object', 'gift')) {
		return $url;
	}

	$receiver = get_entity($entity->receiver);
	return "gifts/" . $receiver->username . "/singlegift?guid=" . $entity->getGUID();
}

/**
 * Add to the user hover menu
 */
function gifts_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];

	if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
		$url = "gifts/".elgg_get_logged_in_user_entity()->username."/sendgift?send_to={$user->guid}";
		$item = new ElggMenuItem('gifts', elgg_echo("gifts:send"), $url);
		$item->setSection('action');
		$return[] = $item;
	}

	return $return;
}

function gifts_widget_urls($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];

	if(empty($result) && ($widget instanceof ElggWidget)) {
		$owner = $widget->getOwnerEntity();
		switch($widget->handler) {
			case "gifts":
				$result = "/gifts/" . elgg_get_logged_in_user_entity()->username . "/all";
				break;
			case "index_gifts":
				$result = "/gifts/" . elgg_get_logged_in_user_entity()->username . "/all";
				break;
		}
	}
	return $result;
}

/**
 * override permissions for gift objects to allow for deleting them both by sender and receiver
 *
 * @param $hook_name
 * @param $entity_type
 * @param $return_value
 * @param $parameters
 * @return unknown_type
 */
function gifts_permissions_check($hook_name, $entity_type, $return_value, $parameters) {

	$gift = $parameters['entity'];
	$user = $parameters['user'];

	$has_access = false;
	if (($gift->getSubtype() == "gift") && (($user->guid == $gift->owner_guid) || ($user->guid == $gift->receiver))) {
		$has_access = true;
	} else {
		return null;
	}
 
	if ($has_access === true) {
		return true;
	} else if ($has_access === false) {
		return false;
	}

	return null;
}
