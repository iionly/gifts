<?php

/**
 * URL Handler
 */
function gifts_url($hook, $type, $url, $params) {
	$entity = $params['entity'];

	if (!($entity instanceof Gifts)) {
		return $url;
	}

	$receiver = get_entity($entity->receiver);
	return "gifts/" . $receiver->username . "/singlegift?guid=" . $entity->getGUID();
}

/**
 * GiftsFile icon url Handler
 */
function giftsfile_url($hook, $type, $url, $params) {
	$entity = $params['entity'];

	if (!($entity instanceof GiftsFile)) {
		return $url;
	}

	$size = elgg_extract('size', $params, 'medium');
	$sizes = array_keys(elgg_get_icon_sizes($entity->getType(), $entity->getSubtype()));
	$sizes[] = 'default';
	if (!in_array($size, $sizes)) {
		$size = 'medium';
	}

	$icon = new ElggIcon();
	$icon->owner_guid = $entity->owner_guid;
	$icon->setFilename("gifts/gift_{$entity->gift_id}_{$size}.jpg");

	$url = elgg_get_inline_url($icon, true);
	if (!$url) {
		if ($size == 'default') {
			$size = 'large';
		}
		$url = elgg_get_simplecache_url("icons/default/$size.png");
	}

	return $url;
}

/**
 * Add to the user hover menu is the user is a friend of logged in user
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

function gifts_entity_menu_setup($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof Gifts) || elgg_in_context('widgets')) {
		return $return;
	}

	if (elgg_is_admin_logged_in() || (elgg_get_logged_in_user_guid() == $entity->owner_guid) || (elgg_get_logged_in_user_guid() == $entity->receiver)) {
		$return[] = ElggMenuItem::factory([
			'name' => 'delete',
			'href' => "action/gifts/delete?guid=$entity->guid",
			'text' => elgg_view_icon('delete'),
			'confirm' => elgg_echo('gifts:deleteconfirm'),
		]);
	}

	return $return;
}

function gifts_widget_urls($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];

	if (empty($result) && ($widget instanceof ElggWidget)) {
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
	if (($gift instanceof Gifts) && (($user->guid == $gift->owner_guid) || ($user->guid == $gift->receiver))) {
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
