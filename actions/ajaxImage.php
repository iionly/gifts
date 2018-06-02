<?php

$gift_id = (int) get_input('ImageID', 0);

$giftsfile_guid = (int) elgg_get_plugin_setting('giftsfileguid_' . $gift_id, 'gifts');
$image = get_entity($giftsfile_guid);

if ($image instanceof GiftsFile) {
	$image_url = $image->getIconURL('default');
	$image_url = elgg_format_url($image_url);
} else {
	$image_url = elgg_get_simplecache_url('icons/default/large.png');
}

$response = [
	'success' => true,
	'html' => elgg_format_element('img', ['class' => 'elgg-photo', 'src' => $image_url], ''),
];

echo json_encode($response);
