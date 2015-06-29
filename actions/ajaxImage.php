<?php

$imageID = get_input('ImageID', 0);

$imagefile = "gift_" . $imageID . "_default.jpg";
$imgfile = elgg_get_plugins_path() . 'gifts/images/' . $imagefile;

if (file_exists($imgfile)) {
	$html = "<img src=\"" . elgg_get_site_url() . 'mod/gifts/images/' . $imagefile . "\" />";
} else {
	$html = "<img src=\"" . elgg_get_site_url() . "mod/gifts/images/noimage.jpg\" />";
}

$response = array('success' => true, 'html' => $html);
echo json_encode($response);
exit();