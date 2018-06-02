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

$send_to = (array) elgg_extract('send_to', $vars);

if (elgg_is_active_plugin('elggx_userpoints')) {
	$useuserpoints  = elgg_get_plugin_setting('useuserpoints', 'gifts');
	if(!$useuserpoints) {
		$useuserpoints = '0';
	} else {
		$useuserpoints = '1';
	}
} else {
	$useuserpoints = '0';
}

if ($useuserpoints == '1') {
	$pTemp = elggx_userpoints_get(elgg_get_logged_in_user_guid());
	$points = $pTemp['approved'];
}
if (!$points) {
	$points = 0;
}

if ($useuserpoints == '1') {
	echo elgg_format_element('div', ['class' => 'mbm'], elgg_echo("gifts:pointssum", [$points]));
}

echo  elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo("gifts:send_to"),
	'name' => 'send_to',
	'values' => $send_to,
	'limit' => 1,
]);

$gift_count = elgg_get_plugin_setting('giftcount' . $i, 'gifts');
$gifts = [];
for ($i = 1; $i <= $gift_count; $i++) {
	if ($gift_i = elgg_get_plugin_setting('gift_' . $i, 'gifts')) {
		$gifts[$i] = $gift_i;
	}
}
echo  elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo("gifts:selectgift"),
	'name' => 'gift_id',
	'id' => 'gift_id',
	'data-useuserpoints' => $useuserpoints,
	'data-points' => $points,
	'options_values' => $gifts,
]);

if ($useuserpoints == '1') {
	echo elgg_format_element('div', ['id' => 'gift_cost'], '&nbsp;');
}
echo elgg_format_element('div', ['id' => 'gift_preview'], '&nbsp;');

echo  elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo("gifts:message"),
	'name' => 'body',
]);

$access = get_default_access();
echo  elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo("gifts:access"),
	'#help' => elgg_echo('gifts:warning_before_saving'),
	'name' => 'access',
		'options_values' => [
		'0'  => elgg_echo('gifts:foureyesaccess'),
		'1'  => elgg_echo('LOGGED_IN'),
		'2'  => elgg_echo('PUBLIC'),
		'-2' => elgg_echo('access:friends:label'),
	],
	'value' => $access,
]);

if ($useuserpoints == '1') {
	// Only show send button if you got enough points
	$footer = elgg_format_element('div', ['id' => 'sendButton'], '&nbsp;');
} else {
	$footer = elgg_view_field([
		'#type' => 'submit',
		'value' => elgg_echo('gifts:send'),
	]);
}

elgg_set_form_footer($footer);
