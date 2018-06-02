<?php

//Load Plugin Settings
$plugin = elgg_get_plugin_from_id('gifts');

$gift_count = (int) $plugin->giftcount;
if (!$gift_count) {
	$gift_count = 20;
}
$useuserpoints = $plugin->useuserpoints;
if (!$useuserpoints) {
	$useuserpoints = '0';
} else {
	$useuserpoints = '1';
}

$vars['giftcount'] = $gift_count;
$vars['useuserpoints'] = $useuserpoints;

for ($i=1; $i <= $gift_count; $i++) {
	$vars['gift_id'] = $i;
	echo elgg_view_form('gifts/savegifts', ['enctype' => 'multipart/form-data'], $vars);
}
