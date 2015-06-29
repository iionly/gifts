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

elgg_require_js('gifts/gifts');

$useuserpoints  = elgg_get_plugin_setting('useuserpoints', 'gifts');
if ($useuserpoints == 1 && function_exists('userpoints_get')) {
	$pTemp = userpoints_get(elgg_get_logged_in_user_guid());
	$points = $pTemp['approved'];
}
if (!$points) {
	$points = 0;
}

$formBody = '';

if ($useuserpoints == 1) {
	$formBody .= "<div class='mbm'>" .  elgg_echo("gifts:pointssum", array($points)) . "</div>";
}

$send_to = get_input('send_to');
// Already send_to?
if ($send_to) {
	//get the user object
	$user = get_user($send_to);

	//draw it
	$formBody .= "<div class='mbm'><label>" .  elgg_echo("gifts:friend") . "</label><br>";
	$formBody .= "<div>" . elgg_view_entity_icon($user, 'tiny') . $user->username . "</div>";
	$formBody .= elgg_view('input/hidden', array('name' => 'send_to', 'id' => 'send_to', 'value' => $send_to));
	$formBody .= "</div>";
} else {
	$friends = array();
	$friends[0] = '';
	foreach($vars['friends'] as $friend) {
		$friends[$friend->guid] = $friend->name;
	}
	$formBody .= "<div class='mbm'><label>" .  elgg_echo("gifts:friend") . "</label><br>";
	$formBody .= elgg_view('input/select', array(
		'name' => 'send_to',
		'options_values' => $friends,
		'value' => 0
	));
	$formBody .= "</div>";
}

$gift_count = elgg_get_plugin_setting('giftcount' . $i, 'gifts');
$gifts = array();
for ($i = 1; $i <= $gift_count; $i++) {
	$gifts[$i] = elgg_get_plugin_setting('gift_' . $i, 'gifts');
}
$formBody .= "<div class='mbm'><label>" .  elgg_echo("gifts:selectgift") . "</label><br>";
$formBody .= elgg_view('input/select', array(
	'name' => 'gift_id',
	'id' => 'gift_id',
	'data-useuserpoints' => $useuserpoints,
	'data-points' => $points,
	'options_values' => $gifts
));
$formBody .= "</div>";

$formBody .= '<div id="gift_cost">&nbsp;</div>';
$formBody .= '<div id="gift_preview">&nbsp;</div>';

$formBody .= "<div class='mbm'><label>" .  elgg_echo("gifts:message") . "</label>";
$formBody .= elgg_view('input/longtext', array('name' => 'body'));
$formBody .= "</div>";

$access = get_default_access();
$formBody .= "<div id='access' class='mbm'><label>" .  elgg_echo("gifts:access") . "</label><br>";
$formBody .= elgg_view('input/select', array(
	'name' => 'access',
	'options_values' => array(
		'0'  => elgg_echo('gifts:foureyesaccess'),
		'1'  => elgg_echo('LOGGED_IN'),
		'2'  => elgg_echo('PUBLIC'),
		'-2' => elgg_echo('access:friends:label')
	),
	'value' => $access
));
$formBody .= elgg_view("output/longtext", array("value" => elgg_echo('gifts:warning_before_saving'), 'class' => 'mtm elgg-subtext'));
$formBody .= "</div>";

$formBody .= "<div>";
if($useuserpoints == 1) {
	// Only show send button if you got enough points
	$formBody .= '<div id="sendButton">&nbsp;</div>';
} else {
	$formBody .= elgg_view('input/submit', array('value' => elgg_echo('gifts:send')));
}
$formBody .= "</div>";

echo elgg_view("input/form", array("action" => "action/gifts/sendgift", 'id' => 'gift_send_form', "body" => $formBody));
