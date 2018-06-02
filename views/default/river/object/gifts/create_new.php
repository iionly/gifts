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

$sender = $vars['item']->getSubjectEntity();
$gift = $vars['item']->getObjectEntity();
$receiver = get_entity($gift->receiver);

if ($sender instanceof ElggUser) {
	$sender_link = elgg_format_element('a', ['href' => $sender->getURL()], $sender->name);
} else {
	$sender_link = elgg_echo('gifts:sender_fallback');
}

if ($receiver instanceof ElggUser) {
	$receiver_link = elgg_format_element('a', ['href' => $receiver->getURL()], $receiver->name);
} else {
	$receiver_link = elgg_echo('gifts:receiver_fallback');
}

$gifttext = (string) elgg_get_plugin_setting('gift_'.$gift->gift_id, 'gifts');
$giftsfile_guid = (int) elgg_get_plugin_setting('giftsfileguid_' . $gift->gift_id, 'gifts');
$image = get_entity($giftsfile_guid);

if ($image instanceof GiftsFile) {
	$imageurl = $image->getIconURL('medium');
} else {
	$imageurl = elgg_get_simplecache_url("icons/default/medium.png");
}
$attachment = elgg_format_element('img', ['class' => 'elgg-photo gifts-photo-item', 'src' => $imageurl], '');

$gift_link = elgg_format_element('a', ['href' => elgg_get_site_url()."gifts/".$receiver->username."/singlegift?guid={$gift->guid}"], $gifttext);

$string = elgg_echo("gifts:river_new", [$receiver_link, $gift_link, $sender_link]);

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'message' => $string,
	'attachments' => $attachment,
]);
