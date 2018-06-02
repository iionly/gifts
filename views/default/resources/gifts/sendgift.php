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

elgg_require_js('gifts/gifts');

$send_to = (int) elgg_extract('send_to', $vars);
$recipient = get_user($send_to);
if ($recipient instanceof ElggUser) {
	$send_to = $recipient->getGUID();
} else {
	$send_to = 0;
}

$logged_in_user = elgg_get_logged_in_user_entity();

elgg_push_breadcrumb(elgg_echo('gifts:menu'), 'gifts/' . $logged_in_user->username. '/index');
$title = elgg_echo('gifts:sendgifts');
elgg_push_breadcrumb($title);

// Add the form
$form_vars = [
	'action' => 'action/gifts/sendgift',
	'id' => 'gift_send_form',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'send_to' => $send_to,
];
$content = elgg_view_form('gifts/send', $form_vars, $body_vars);

// Format page
$body = elgg_view_layout('content', [
	'content' => $content,
	'filter' => '',
	'title' => $title,
]);

// Draw it
echo elgg_view_page($title, $body);
