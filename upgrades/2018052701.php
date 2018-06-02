<?php

/**
 * Create GiftsFile entities for existing gift images, move the image files
 * from plugin folder image subdirectory to site_entity data folder
 *
 */

// prevent timeout when script is running
set_time_limit(0);

$plugin = elgg_get_plugin_from_id('gifts');

$ImgDir = dirname(dirname(__FILE__))."/images";
$site_entity = elgg_get_site_entity();
$site_guid = $site_entity->guid;
$prefix = 'gifts';

$gift_count = (int) $plugin->giftcount;
if (!$gift_count) {
	$gift_count = 99;
}

for ($i=1; $i <= $gift_count; $i++) {

	$imageid = 'gift_' . $i;

	$old_gifts_file = $ImgDir . '/' . $imageid . '.jpg';

	if (file_exists($old_gifts_file)) {

		$giftsfile = new GiftsFile();
		$giftsfile->owner_guid = $site_guid;
		$giftsfile->container_guid = $site_guid;
		$giftsfile->access_id = ACCESS_PUBLIC;
		$giftsfile->title = "gift_{$i}";
		$giftsfile->description = "gift_{$i}";
		$giftsfile->upload_time = time();
		$giftsfile->gift_id = $i;
		$giftsfile->originalfilename = "gift_{$i}.jpg";
		$giftsfile->setFilename("$prefix/gift_{$i}.jpg");
		$giftsfile->filestore_prefix = $prefix;
		$giftsfile->open('write');
		$giftsfile->close();
		$filestorename = $giftsfile->getFilenameOnFilestore();
		copy($old_gifts_file, $filestorename);

		$mime_type = $giftsfile->detectMimeType();
		$giftsfile->setMimeType($mime_type);
		$giftsfile->simpletype = elgg_get_file_simple_type($mime_type);
		
		$giftsfile->save();

		elgg_set_plugin_setting('giftsfileguid_' . $i, $giftsfile->getGUID(), 'gifts');

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
				$tmp->setFilename("gifts/gift_{$i}_{$imagesize}.jpg");
				$tmp->open('write');
				$tmp->close();
				$destination = $tmp->getFilenameOnFilestore();
				elgg_save_resized_image($filestorename, $destination, $params);
			}
		}
	}
}
