<?php

$GiftID = (string) get_input('GiftID');

$points = elgg_get_plugin_setting('giftpoints_' . $GiftID, 'gifts');
if ($points == '') {
	$points = 0;
}

$response = [
	'success' => true,
	'points' => $points,
];

echo json_encode($response);
