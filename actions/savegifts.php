<?php

$gift_count = (int) get_input('giftcount');
$gift_id = (int) get_input('gift_id');
$giftsfile_guid = (int) get_input('giftsfile_guid');

// Params array
$params = (array) get_input('params');

// If no userpoints defined, set to 0 points
$params["giftpoints_{$gift_id}"] = (int) elgg_extract("giftpoints_{$gift_id}", $params, 0, false);

foreach ($params as $k => $v) {
	if (!elgg_set_plugin_setting($k, $v, 'gifts')) {
		return elgg_error_response(elgg_echo('gifts:settings:savefail'));
	}
}

// check if upload attempted and failed
$uploaded_files = elgg_get_uploaded_files("giftimage_{$gift_id}");
$uploaded_file = array_shift($uploaded_files);
if ($uploaded_file && !$uploaded_file->isValid()) {
	$error = elgg_get_friendly_upload_error($uploaded_file->getError());
	return elgg_error_response($error);
}

// check whether this is a new file or an edit
$new_file = true;
if ($giftsfile_guid > 0) {
	$new_file = false;
}

$site_entity = elgg_get_site_entity();
$site_guid = $site_entity->guid;
if ($new_file) {
	$giftsfile = new GiftsFile();
	$giftsfile->owner_guid = $site_guid;
	$giftsfile->container_guid = $site_guid;
	$giftsfile->access_id = ACCESS_PUBLIC;
	$giftsfile->title = "gift_{$gift_id}";
	$giftsfile->description = "gift_{$gift_id}";
	$giftsfile->upload_time = time();
	$giftsfile->gift_id = $gift_id;
	$giftsfile->originalfilename = "gift_{$gift_id}.jpg";
	$prefix = 'gifts';
	$giftsfile->setFilename("$prefix/gift_{$gift_id}.jpg");
	$giftsfile->filestore_prefix = $prefix;
} else {
	$giftsfile = get_entity($giftsfile_guid);
	elgg_set_plugin_setting('giftsfileguid_' . $gift_id, $giftsfile->getGUID(), 'gifts');
}

// Now save the uploaded gifts image file and create the thumbnails
if ($uploaded_file && $uploaded_file->isValid()) {

	$uploaded = false;
	$filestorename = $giftsfile->getFilenameOnFilestore();
	try {
		$uploaded = $uploaded_file->move(pathinfo($filestorename, PATHINFO_DIRNAME), pathinfo($filestorename, PATHINFO_BASENAME));
	} catch (FileException $ex) {
		return elgg_error_response(elgg_echo('gifts:settings:savefail'));
	}

	$guid = 0;
	if ($uploaded) {
		$mime_type = $giftsfile->detectMimeType();
		$giftsfile->setMimeType($mime_type);
		$giftsfile->simpletype = elgg_get_file_simple_type($mime_type);
		$guid = $giftsfile->save();
	}

	if (!$guid) {
		return elgg_error_response(elgg_echo('gifts:settings:savefail'));
	}

	elgg_set_plugin_setting('giftsfileguid_' . $gift_id, $giftsfile->getGUID(), 'gifts');

	$icon_sizes = elgg_get_icon_sizes($giftsfile->type, $giftsfile->getSubtype());
	$icon_sizes['default'] = [
			'w' => 999,
			'h' => 999,
			'square' => false,
			'upscale' => false,
	];
	$imagesizes = array_keys($icon_sizes);
	foreach ($imagesizes as $imagesize) {
		if ($icon_sizes[$imagesize]) {
			$params = [
				'w' => $icon_sizes[$imagesize]['w'],
				'h' => $icon_sizes[$imagesize]['h'],
				'square' => $icon_sizes[$imagesize]['square'],
				'upscale' => $icon_sizes[$imagesize]['upscale'],
			];

			$tmp = new ElggFile();
			$tmp->owner_guid = $site_guid;
			$tmp->setFilename("gifts/gift_{$gift_id}_{$imagesize}.jpg");
			$tmp->open('write');
			$tmp->close();
			$destination = $tmp->getFilenameOnFilestore();
			elgg_save_resized_image($filestorename, $destination, $params);
		}
	}
}

return elgg_ok_response('', elgg_echo('gifts:settings:saveok'), REFERER);
