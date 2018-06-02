<?php

$gift_count = elgg_extract('giftcount', $vars);
$useuserpoints = elgg_extract('useuserpoints', $vars);
$gift_id = elgg_extract('gift_id', $vars);

if ($gift_id > $gift_count) {
	return;
}

$title = elgg_echo('gifts:settings:title') . ' #' . $gift_id;

$content = elgg_view_field([
	'#type' => 'hidden',
	'name' => 'giftcount',
	'value' => $giftcount,
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'gift_id',
	'value' => $gift_id,
]);

$content .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('gifts:settings:name'),
	'name' => "params[gift_{$gift_id}]",
	'value' => elgg_get_plugin_setting('gift_' . $gift_id, 'gifts'),
	'required' => true,
]);

if ($useuserpoints == '1' && elgg_is_active_plugin('elggx_userpoints')) {
	$content .= elgg_view_field([
		'#type' => 'number',
		'#label' => elgg_echo('gifts:settings:userpoints'),
		'name' => "params[giftpoints_{$gift_id}]",
		'value' => (int) elgg_get_plugin_setting('giftpoints_' . $gift_id, 'gifts'),
		'min' => 0,
		'step' => 1,
		'required' => true,
	]);
}

$content .= elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('gifts:settings:image'),
	'name' => "giftimage_{$gift_id}",
]);

// Show Image if already uploaded
$giftsfile_guid = (int) elgg_get_plugin_setting('giftsfileguid_' . $gift_id, 'gifts');
$image = get_entity($giftsfile_guid);
if ($image instanceof GiftsFile) {
	$content .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'giftsfile_guid',
		'value' => $image->getGUID(),
	]);
	$image_url = $image->getIconURL('medium');
	$image_url = elgg_format_url($image_url);
	$content .= elgg_format_element('img', ['class' => 'elgg-photo', 'src' => $image_url], '');
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);

echo elgg_view_module('inline', $title, $content);
