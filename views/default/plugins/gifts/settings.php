<?php

/**
 * Gifts plugin settings form
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

if (gifts_is_upgrade_available()) {
	echo elgg_format_element('div', ['class' => 'elgg-admin-notices'], elgg_autop(elgg_view('output/url', [
		'text' => elgg_echo('gifts:upgrade'),
		'href' => 'action/gifts/upgrade',
		'is_action' => true,
	])));
}

// show navigation tabs
echo elgg_view('gifts/tabs', ['tab' => 'settings']);

$showallgifts = $plugin->showallgifts;
if(!$showallgifts) {
	$showallgifts = '0';
} else {
	$showallgifts = '1';
}
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('gifts:settings:showallgifts'),
	'name' => 'params[showallgifts]',
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
	'value' => $showallgifts,
]);

// Userpoints: check if Elggx Userpoint plugin is enabled
if (elgg_is_active_plugin('elggx_userpoints')) {
	$useuserpoints = $plugin->useuserpoints;
	if(!$useuserpoints) {
		$useuserpoints = '0';
	} else {
		$useuserpoints = '1';
	}
	echo elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('gifts:settings:useuserpoints'),
		'name' => 'params[useuserpoints]',
		'options_values' => [
			'1' => elgg_echo('option:yes'),
			'0' => elgg_echo('option:no'),
		],
		'value' => $useuserpoints,
	]);
}

$giftcount = (int) $plugin->giftcount;
if(!$giftcount) {
	$giftcount = 20;
}
echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('gifts:settings:number'),
	'name' => 'params[giftcount]',
	'value' => $giftcount,
	'min' => 1,
	'max' => 99,
	'step' => 1,
]);
