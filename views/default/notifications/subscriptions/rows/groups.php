<?php
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$methods = array_keys(_elgg_services()->notifications->getMethods());

// Fetch all groups the user is a member of
$groups = elgg_get_entities([
	'types' => 'group',
	'limit' => 0,
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false,
	'sort_by' => [
		'property' => 'name',
		'direction' => 'ASC',
	],
]);

$groups_list = [];
foreach ($groups as $group) {
	$icon = elgg_view_entity_icon($group, 'tiny', [
		'use_link' => false,
	]);
	$name = $group->getDisplayName();

	// Check notification relationships for each method
	$relationships = [];
	foreach ($methods as $method) {
		if (check_entity_relationship($user->guid, "notify{$method}", $group->guid)) {
			$relationships[] = "notify{$method}";
		}
	}

	$groups_list[$group->guid] = [
		'view' => elgg_view_image_block($icon, $name),
		'relationships' => $relationships,
	];
}

if (empty($groups_list)) {
	return;
}

$groups_count = count($groups_list);
$group_guids = array_keys($groups_list);

foreach ($groups_list as $group_guid => $group_data) {
	?>
	<tr class="elgg-subscriptions-group">
		<td class="namefield elgg-subscriptions-type-label">
			<?php echo $group_data['view']; ?>
		</td>
		<?php
		foreach ($methods as $method) {
			$checked = in_array("notify{$method}", $group_data['relationships']);
			$checkbox = elgg_view('input/checkbox', [
				'name' => "{$method}subscriptions[]",
				'value' => $group_guid,
				'default' => false,
				'checked' => $checked,
				'class' => 'elgg-subscriptions-toggle',
				'data-method' => $method,
				'data-guid' => $group_guid,
			]);

			$link = elgg_view('output/url', [
				'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
				'text' => $checkbox,
				'href' => false,
			]);

			echo elgg_format_element('td', ['class' => "{$method}togglefield elgg-subscriptions-toggle-cell"], $link);
		}

		echo elgg_format_element('td', [], '&nbsp;');
		?>
	</tr>
	<?php
}
