<?php

// Get input data
$guid = (int) get_input('guid');

// Make sure we actually have permission to delete
$gift = get_entity($guid);

if (!$gift) {
	return elgg_error_response(elgg_echo('gift:delete:error'), REFERER);
}

if (!($gift instanceof Gifts) || !($gift->canEdit())) {
	return elgg_error_response(elgg_echo('gift:delete:error'), REFERER);
}

if (!$gift->delete()) {
	return elgg_error_response(elgg_echo('gift:delete:error'), REFERER);
}

return elgg_ok_response('', elgg_echo('gift:delete:success'), REFERER);
