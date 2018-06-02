<?php

/**
 * Gifts index page widget
 **/

$widget = elgg_extract('entity', $vars);
$num_display = (int) $widget->gifts_count ?: 4;

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => Gifts::SUBTYPE,
	'limit' => $num_display,
	'pagination' => false,
	'item_view' => 'gifts/list/gifts_widget',
	'no_results' => elgg_echo('gifts:nogifts'),
]);
