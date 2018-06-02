<?php
/**
 * Gifts widget for index page edit view
 */

$widget = elgg_extract('entity', $vars);
$num_display = (int) $widget->gifts_count ?: 4;

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('gifts:widget:num_display'),
	'name' => 'params[gifts_count]',
	'value' => $num_display,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
