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

// THANK YOU DDFUSION
// Added Fix from DDFusion

$performed_by = $vars['item']->getSubjectEntity();
$performed_on = $vars['item']->getObjectEntity();
$object = $vars['item']->getObjectEntity();

if ($performed_by instanceof ElggUser) {
	$person_link = elgg_format_element('a', ['href' => $performed_by->getURL()], $performed_by->name);
} else {
	$person_link = elgg_echo('gifts:sender_fallback');
}

if ($performed_on instanceof ElggUser) {
	$object_link = elgg_format_element('a', ['href' => $performed_on->getURL()], $performed_on->name);
} else {
	$object_link = elgg_echo('gifts:receiver_fallback');
}

$gift = elgg_format_element('a', ['href' => elgg_get_site_url()."gifts/".elgg_get_logged_in_user_entity()->username."/index"], elgg_echo("gifts:gift"));

$string = elgg_echo("gifts:river", [$object_link, $gift])  . $person_link;

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'message' => $string,
]);
