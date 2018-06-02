<?php

/**
 * Gifts profile widget - this displays a users gifts on their profile
 **/

$widget = elgg_extract('entity', $vars);
$num_display = (int) $widget->num_display ?: 4;

//the page owner
$owner = elgg_get_page_owner_guid();

$access = elgg_get_ignore_access();
if (elgg_get_logged_in_user_guid() == $owner) {
	$access = elgg_set_ignore_access(true);
}

echo elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => Gifts::SUBTYPE,
	'limit' => $num_display,
	'metadata_name_value_pair' => [
		'name' => 'receiver',
		'value' => $owner,
		'operand' => '=',
	],
	'pagination' => false,
	'item_view' => 'gifts/list/gifts_widget',
	'no_results' => elgg_echo('gifts:nogifts'),
]);

elgg_set_ignore_access($access);
