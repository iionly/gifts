<?php

/**
 * Are there upgrade scripts to be run?
 *
 * @return bool
 */
function gifts_is_upgrade_available() {
	// sets $version based on code
	require_once elgg_get_plugins_path() . "gifts/version.php";

	$local_version = elgg_get_plugin_setting('up_version', 'gifts');

	if ($local_version === null) {
		// check if installation already in use
		$gifts_count = elgg_get_entities([
			'type' => 'object',
			'subtype' => Gifts::SUBTYPE,
			'count' => true,
		]);

		$giftsfile_count = elgg_get_entities([
			'type' => 'object',
			'subtype' => GiftsFile::SUBTYPE,
			'count' => true,
		]);

		if (($gifts_count > 0) && ($giftsfile_count < 1)) {
			// no version set yet but requires upgrade
			$local_version = 0;
		} else {
			// set initial version for new install
			elgg_set_plugin_setting('up_version', $version, 'gifts');
			$local_version = $version;
		}
	}

	if ($local_version == $version) {
		return false;
	} else {
		return true;
	}
}
