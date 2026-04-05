<?php
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$methods = array_keys(_elgg_services()->notifications->getMethods());

$dbprefix = elgg_get_config('dbprefix');
$groups = new ElggBatch('elgg_get_entities', array(
	'selects' => array('GROUP_CONCAT(ers.relationship) as relationships'),
	'types' => 'group',
	'limit' => 0,
	'joins' => array(
		// WARNING: groups_entity subtable removed in Elgg 3.0 — rewrite this SQL
		"JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid",
		"JOIN {$dbprefix}entity_relationships ers ON e.guid = ers.guid_two AND ers.guid_one = $user->guid",
	),
	'wheres' => array(
		"ers.relationship = 'member' OR ers.relationship LIKE 'notify%'"
	),
	'group_by' => 'e.guid',
	'order_by' => 'ge.name',
		));

$groups_list = array();
foreach ($groups as $group) {
	$icon = elgg_view_entity_icon($group, 'tiny', array(
		'use_link' => false,
	));
	$name = $group->name;
	$relationships = array();
	$relationships_concat = $group->getVolatileData('select:relationships');
	if ($relationships_concat) {
		$relationships = explode(',', $relationships_concat);
	}
	$groups_list[$group->guid] = array(
		'view' => elgg_view_image_block($icon, $name),
		'relationships' => $relationships,
	);
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
			$checkbox = elgg_view('input/checkbox', array(
				'name' => "{$method}subscriptions[]",
				//'id' => "{$method}checkbox",
				//'onclick' => "adjust{$method}('{$method}{$group_guid}')",
				'value' => $group_guid,
				'default' => false,
				'checked' => $checked,
				'class' => 'elgg-subscriptions-toggle',
				'data-method' => $method,
				'data-guid' => $group_guid,
			));

			$link = elgg_view('output/url', array(
				//'id' => "{$method}{$group_guid}",
				'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
				//'onclick' => "adjust{$method}_alt('{$method}{$group_guid}');",
				'text' => $checkbox,
				'href' => false,
			));

			echo elgg_format_element('td', ['class' => "{$method}togglefield elgg-subscriptions-toggle-cell"], $link);
		}
		echo elgg_format_element('td', [], "&nbsp;");
		?>
	</tr>
	<?php
}