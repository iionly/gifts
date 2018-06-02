<?php
/**
 * Listing view for gifts in wigets of Gifts plugin
 *
 * @uses $vars['entity'] the gifts to list
 */

/* @var $gift Gifts */
$gift = elgg_extract('entity', $vars);

// Select Receiver and Sender
$sender = get_entity($gift->owner_guid);
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
	$imageurl = $image->getIconURL('tiny');
} else {
	$imageurl = elgg_get_simplecache_url("icons/default/tiny.png");
}

$gift_link = elgg_format_element('a', ['href' => elgg_get_site_url()."gifts/".$receiver->username."/singlegift?guid={$gift->guid}"], $gifttext);

$content = elgg_format_element('div', ['class' => 'gifts_widget_icon'], elgg_format_element('img', ['class' => 'elgg-photo', 'src' => $imageurl], ''));
$content = elgg_format_element('a', ['href' => elgg_get_site_url()."gifts/" . $receiver->username . "/singlegift?guid={$gift->guid}"], $content);
$content .= elgg_format_element('div', ['class' => 'gifts_widget_content'], elgg_echo("gifts:object", [$receiver_link, $gift_link, $sender_link]));

echo elgg_format_element('div', ['class' => 'gifts_widget_wrapper'], $content);
