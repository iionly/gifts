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

// get the form input
$receiver_guid = get_input('send_to');
$gift_id = get_input('gift_id');
$body = get_input('body');
$cost = get_input('giftcost');
$access = get_input('access');

$receiver = get_entity($receiver_guid);

$sender = elgg_get_logged_in_user_entity();
$sender_guid = $sender->getGUID();

// No Friend selected?
if (!($receiver instanceof ElggUser) || empty($gift_id)) {
	register_error(elgg_echo("gifts:blank"));
	forward("gifts/".$sender->name."/sendgift");
}

// Userpoints
$useuserpoints  = elgg_get_plugin_setting('useuserpoints', 'gifts');
if($useuserpoints == 1 && function_exists('userpoints_subtract')) {
	$pTemp = userpoints_get($sender_guid);
	$points = $pTemp['approved'];

	// Set new Point Value
	if(userpoints_subtract($sender_guid, $cost, 'gifts')) {
		system_message(elgg_echo('gifts:pointsuccess'));
	} else {
		system_message(elgg_echo('gifts:pointfail'));
	}
}

// create a gifts object
$gift = new ElggObject();
$gift->description = $body;
$gift->receiver = $receiver_guid;
$gift->gift_id = $gift_id;
$gift->subtype = "gift";

$gift->access_id = $access;

$gift->owner_guid = $sender_guid;

// save to database
$gift->save();

// send mail notification
$msgto_language = ($receiver->language) ? $receiver->language : (($site_language = elgg_get_config('language')) ? $site_language : 'en');
$subject = elgg_echo('gifts:mail:subject', array(), $msgto_language);
$message = elgg_echo('gifts:mail:body', array($sender->name, elgg_get_site_url() . "gifts/" . $receiver->username . "/singlegift?guid=" . $gift->getGUID()), $msgto_language);
notify_user($receiver_guid, elgg_get_logged_in_user_guid(), $subject, $message, array(
	'object' => $gift,
	'action' => 'send',
	'summary' => $subject
));

// Add to river
if ((elgg_get_plugin_setting('showallgifts', 'gifts') == 1) && ($access != ACCESS_PRIVATE)) {
	elgg_create_river_item(array(
		'view' => 'river/object/gifts/create_new',
		'action_type' => 'gifts',
		'subject_guid' => $gift->owner_guid,
		'object_guid' => $gift->getGUID()
	));
}
system_message(elgg_echo('gifts:sendok'));
// display gift
forward("gifts/".$sender->name."/sent");
