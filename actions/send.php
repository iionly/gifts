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
$receiver_guid = (array) get_input('send_to');
$receiver_guid = (int) elgg_extract(0, $receiver_guid);
$gift_id = (int) get_input('gift_id');
$body = (string) get_input('body');
$cost = (int) get_input('giftcost');
$access = (int) get_input('access');

$receiver = get_entity($receiver_guid);

$sender = elgg_get_logged_in_user_entity();
$sender_guid = $sender->getGUID();

// No user of gift selected?
if (!($receiver instanceof ElggUser) || !($gift_id)) {
	return elgg_error_response(elgg_echo('gifts:blank'), "gifts/{$sender->name}/sendgift");
}

// Trying to send a gift to yourself?
if ($receiver_guid == $sender_guid) {
	return elgg_error_response(elgg_echo('gifts:gift_self'), "gifts/{$sender->name}/sendgift");
}

// Userpoints
$useuserpoints = (string) elgg_get_plugin_setting('useuserpoints', 'gifts');
if ($useuserpoints == '1' && function_exists('elggx_userpoints_subtract')) {
	$pTemp = elggx_userpoints_get($sender_guid);
	$points = $pTemp['approved'];

	// Set new Point Value
	if (!elggx_userpoints_subtract($sender_guid, $cost, 'gifts')) {
		return elgg_error_response(elgg_echo('gifts:pointfail'), "gifts/{$sender->name}/sendgift");
	} else {
		system_message(elgg_echo('gifts:pointsuccess'));
	}
}

// create a gifts object
$gift = new Gifts();
$gift->owner_guid = $sender_guid;
$gift->receiver = $receiver_guid;
$gift->description = $body;
$gift->gift_id = $gift_id;
$gift->access_id = $access;

// save to database
$gift->save();

// send mail notification
$msgto_language = ($receiver->language) ? $receiver->language : (($site_language = elgg_get_config('language')) ? $site_language : 'en');
$subject = elgg_echo('gifts:mail:subject', [], $msgto_language);
$message = elgg_echo('gifts:mail:body', [$sender->name, elgg_get_site_url() . "gifts/" . $receiver->username . "/singlegift?guid=" . $gift->getGUID()], $msgto_language);
notify_user($receiver_guid, elgg_get_logged_in_user_guid(), $subject, $message, [
	'object' => $gift,
	'action' => 'send',
	'summary' => $subject,
]);

// Add to river
$showallgifts = (string) elgg_get_plugin_setting('showallgifts', 'gifts');
if (($showallgifts == '1') && ($access != ACCESS_PRIVATE)) {
	elgg_create_river_item([
		'view' => 'river/object/gifts/create_new',
		'action_type' => 'gifts',
		'subject_guid' => $gift->owner_guid,
		'object_guid' => $gift->getGUID(),
	]);
}

// display sent gift
return elgg_ok_response('', elgg_echo('gifts:sendok'), "gifts/{$sender->name}/sent");
