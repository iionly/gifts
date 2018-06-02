<?php

// register classes
if (get_subtype_id('object', 'giftsfile')) {
	update_subtype('object', 'giftsfile', 'GiftsFile');
} else {
	add_subtype('object', 'giftsfile', 'GiftsFile');
}
if (get_subtype_id('object', 'gift')) {
	update_subtype('object', 'gift', 'Gifts');
} else {
	add_subtype('object', 'gift', 'Gifts');
}

// Set Plugin Version for Update Checks
elgg_set_plugin_setting('version', '2.3.1', 'gifts');
