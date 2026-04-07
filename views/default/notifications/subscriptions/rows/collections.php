<?php
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

function subscriptions_compare_by_name($a, $b) {
	$an = $a['name'];
	$bn = $b['name'];

	$result = strnatcmp($an, $bn);
	return $result;
}

$methods = array_keys(_elgg_services()->notifications->getMethods());

// Fetch all friends of the user
$subscriptions = elgg_get_entities([
	'types' => 'user',
	'limit' => 0,
	'relationship' => 'friend',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false,
]);

$subscriptions_list = array();
foreach ($subscriptions as $subscription) {
	$icon = elgg_view_entity_icon($subscription, 'tiny', array(
		'use_hover' => false,
		'use_link' => false,
	));
	$name = $subscription->getDisplayName();

	// Check notification relationships for each method
	$relationships = array();
	foreach ($methods as $method) {
		if (check_entity_relationship($user->guid, "notify{$method}", $subscription->guid)) {
			$relationships[] = "notify{$method}";
		}
	}

	$subscriptions_list[$subscription->guid] = array(
		'name' => $name,
		'view' => elgg_view_image_block($icon, $name),
		'relationships' => $relationships,
	);
}

if (empty($subscriptions_list)) {
	return;
}

usort($subscriptions_list, 'subscriptions_compare_by_name');

$subscriptions_count = count($subscriptions_list);
$subscription_guids = array_keys($subscriptions_list);

$collection_id = -1;
?>
<tr class="elgg-subscriptions-collection">
	<td class="namefield elgg-subscriptions-type-label">
		<?php
		if ($subscriptions_count) {
			echo elgg_view('output/url', array(
				'text' => elgg_echo('notifications:users:all') . " ($subscriptions_count) " . elgg_view_icon('angle-right'),
				'href' => '#',
				'class' => 'elgg-subscriptions-show-members',
			));
		} else {
			echo elgg_format_element('span', [], elgg_echo('notifications:users:all') . " ($subscriptions_count)");
		}
		?>
	</td>

	<?php
	foreach ($methods as $method) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = (array) $user->$metaname;

		$checked = in_array($collection_id, $collections_preferences);

		$checkbox = elgg_view('input/checkbox', array(
			'name' => "{$method}collections[]",
			'value' => $collection_id,
			'default' => false,
			'checked' => $checked,
			'class' => 'elgg-subscriptions-toggle',
			'data-collection-id' => $collection_id,
			'data-members' => json_encode($subscription_guids),
			'data-method' => $method,
		));

		$link = elgg_view('output/url', array(
			'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
			'text' => $checkbox,
			'href' => false,
		));

		echo elgg_format_element('td', ['class' => "{$method}togglefield elgg-subscriptions-toggle-cell"], $link);
	}
	echo elgg_format_element('td', [], "&nbsp;");
	?>
</tr>

<?php
foreach ($subscriptions_list as $subscription_guid => $subscription_data) {
	?>
	<tr class="elgg-subscriptions-collection-member">
		<td class="namefield elgg-subscriptions-type-label">
			<?php echo $subscription_data['view']; ?>
		</td>
		<?php
		foreach ($methods as $method) {
			$checked = in_array("notify{$method}", $subscription_data['relationships']);
			$checkbox = elgg_view('input/checkbox', array(
				'name' => "{$method}subscriptions[]",
				'value' => $subscription_guid,
				'default' => false,
				'checked' => $checked,
				'class' => 'elgg-subscriptions-toggle',
				'data-method' => $method,
				'data-member-of' => $collection_id,
				'data-guid' => $subscription_guid,
			));

			$link = elgg_view('output/url', array(
				'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
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

$collections = $user->getOwnedAccessCollections();
if (empty($collections)) {
	return;
}

foreach ($collections as $collection) {
	$collection_id = $collection->id;
	$members = $collection->getMembers(['guids_only' => true]);
	$members_list = array();
	foreach ($members as $member_guid) {
		$member_guid = (int) $member_guid;
		if (!isset($subscriptions_list[$member_guid])) {
			$member = get_entity($member_guid);
			if (!$member) {
				continue;
			}
			$icon = elgg_view_entity_icon($member, 'tiny', array(
				'use_hover' => false,
				'use_link' => false,
			));
			$name = $member->getDisplayName();
			$relationships = array();
			foreach ($methods as $method) {
				if (check_entity_relationship($user->guid, "notify{$method}", $member->guid)) {
					$relationships[] = "notify{$method}";
				}
			}
			$subscriptions_list[$member->guid] = array(
				'name' => $name,
				'view' => elgg_view_image_block($icon, $name),
				'relationships' => $relationships,
			);
		}
		$members_list[$member_guid] = $subscriptions_list[$member_guid];
	}
	usort($members_list, 'subscriptions_compare_by_name');
	$members_count = count($members_list);
	?>
	<tr class="elgg-subscriptions-collection">
		<td class="namefield elgg-subscriptions-type-label">
			<?php
			if ($members_count) {
				echo elgg_view('output/url', array(
					'text' => $collection->name . " ($members_count) " . elgg_view_icon('angle-right'),
					'href' => '#',
					'class' => 'elgg-subscriptions-show-members',
				));
			} else {
				echo elgg_format_element('span', [], $collection->name . " ($members_count) ");
			}
			?>
		</td>

		<?php
		foreach ($methods as $method) {
			$metaname = 'collections_notifications_preferences_' . $method;
			$collections_preferences = (array) $user->$metaname;

			$checked = in_array($collection_id, $collections_preferences);

			$checkbox = elgg_view('input/checkbox', array(
				'name' => "{$method}collections[]",
				'value' => $collection_id,
				'default' => false,
				'checked' => $checked,
				'class' => 'elgg-subscriptions-toggle',
				'data-collection-id' => $collection_id,
				'data-members' => json_encode(array_keys($members_list)),
				'data-method' => $method,
			));

			$link = elgg_view('output/url', array(
				'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
				'text' => $checkbox,
				'href' => false,
			));

			echo elgg_format_element('td', ['class' => "{$method}togglefield elgg-subscriptions-toggle-cell"], $link);
		}
		echo elgg_format_element('td', [], "&nbsp;");
		?>
	</tr>

	<?php
	foreach ($members_list as $member_guid => $member_data) {
		?>
		<tr class="elgg-subscriptions-collection-member">
			<td class="namefield elgg-subscriptions-type-label">
				<?php echo $member_data['view']; ?>
			</td>
			<?php
			foreach ($methods as $method) {
				$checked = in_array("notify{$method}", $member_data['relationships']);
				$checkbox = elgg_view('input/checkbox', array(
					'name' => "{$method}subscriptions[]",
					'value' => $member_guid,
					'default' => false,
					'checked' => $checked,
					'class' => 'elgg-subscriptions-toggle',
					'data-method' => $method,
					'data-member-of' => $collection_id,
					'data-guid' => $member_guid,
				));

				$link = elgg_view('output/url', array(
					'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
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
}
