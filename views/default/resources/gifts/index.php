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

elgg_push_breadcrumb(elgg_echo('gifts:menu'), 'gifts/' . elgg_get_logged_in_user_entity()->username. '/index');
$title = elgg_echo('gifts:yourgifts');
elgg_push_breadcrumb($title);

if (elgg_is_logged_in()) {
	elgg_register_menu_item('title', [
		'name' => 'sendgift',
		'href' => "gifts/" . elgg_get_logged_in_user_entity()->username . "/sendgift",
		'text' => elgg_echo('gifts:sendgifts'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

$access = elgg_set_ignore_access(true);

$content = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => Gifts::SUBTYPE,
	'metadata_name_value_pair' => [
		'name' => 'receiver',
		'value' => elgg_get_logged_in_user_guid(),
		'operand' => '=',
	],
	'no_results' => elgg_echo('gifts:nogifts'),
]);

// Format page
$body = elgg_view_layout('content', [
	'content' => $content,
	'filter' => '',
	'title' => $title,
]);

// Draw it
echo elgg_view_page($title, $body);

elgg_set_ignore_access($access);
