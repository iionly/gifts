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

// Select Receiver and Sender
$gift = elgg_extract('entity', $vars);
$sender = get_entity($gift->owner_guid);
$receiver = get_entity($gift->receiver);
$message = $gift->description;

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

$gifttext = (string) elgg_get_plugin_setting("gift_{$gift->gift_id}", 'gifts');
$giftsfile_guid = (int) elgg_get_plugin_setting('giftsfileguid_' . $gift->gift_id, 'gifts');
$image = get_entity($giftsfile_guid);

if ($image instanceof GiftsFile) {
	$imageurl = $image->getIconURL('default');
} else {
	$imageurl = elgg_get_simplecache_url("icons/default/large.png");
}

$controls = elgg_view_menu('entity', [
	'entity' => $gift,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
]);

$subtitle = [];
$subtitle[] = elgg_echo("gifts:object", [$receiver_link, $gifttext, $sender_link]);
$subtitle[] = elgg_view_friendly_time($gift->time_created);

$content = elgg_format_element('p', [], elgg_format_element('img', ['class' => 'elgg-photo', 'src' => $imageurl], ''));
if ($message) {
	$content .= elgg_format_element('div', ['class' => 'mbs'], elgg_format_element('label', [], elgg_echo('gifts:message')));
	$content .= elgg_view('output/longtext', [
		'value' => $message,
	]);
}
$content = elgg_format_element('div', [], $content);

echo elgg_view('object/elements/summary', [
	'entity' => $gift,
	'title' => $gifttext,
	'metadata' => $controls,
	'subtitle' => implode('<br>', $subtitle),
	'content' => $content,
]);
