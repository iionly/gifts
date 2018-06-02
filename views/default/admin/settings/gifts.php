<?php

/**
 * Elgg Gifts plugin
 * Send gifts to you friends
 *
 * @package Gifts
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Christian Heckelmann
 * @copyright Christian Heckelmann
 * @link http://www.heckelmann.info
 *
 * updated by iionly (iionly@gmx.de)
 */

if (gifts_is_upgrade_available()) {
	echo elgg_format_element('div', ['class' => 'elgg-admin-notices'], elgg_autop(elgg_view('output/url', [
		'text' => elgg_echo('gifts:upgrade'),
		'href' => 'action/gifts/upgrade',
		'is_action' => true,
	])));
}

$tab = get_input('tab', 'giftsettings');

echo elgg_view('gifts/tabs', [
	'tab' => $tab,
]);

if (elgg_view_exists("gifts/{$tab}")) {
	echo elgg_view("gifts/{$tab}");
}
