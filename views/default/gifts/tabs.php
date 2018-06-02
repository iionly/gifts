<?php

$selected_tab = elgg_extract('tab', $vars);

$base_url = 'admin/settings/gifts';

$tabs = [
	'settings' => [
		'href' => 'admin/plugin_settings/gifts',
	],
	'giftsettings' => [],
];

$params = [
	'tabs' => [],
];

foreach ($tabs as $tab => $tab_settings) {

	$href = elgg_extract('href', $tab_settings);
	if (empty($href)) {
		$href = elgg_http_add_url_query_elements($base_url, [
			'tab' => $tab,
		]);
	}

	$params['tabs'][] = [
		'title' => elgg_echo("gifts:{$tab}"),
		'url' => $href,
		'selected' => ($tab === $selected_tab),
	];
}

echo elgg_view('navigation/tabs', $params);
