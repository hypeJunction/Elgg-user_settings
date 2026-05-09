<?php
$user = elgg_extract('user', $vars);
$methods = array_keys(_elgg_services()->notifications->getMethods());
?>

<tr class="elgg-subscriptions-personal">
	<td class="namefield elgg-subscriptions-type-label">
		<?php echo elgg_echo('notifications:subscriptions:personal:description') ?>
	</td>

	<?php
	foreach ($methods as $method) {
		$notification_settings = $user instanceof ElggUser ? $user->getNotificationSettings() : [];

		$checked = !empty($notification_settings[$method]);
		$checkbox = elgg_view('input/checkbox', [
			'name' => "{$method}personal",
			//'id' => "{$method}checkbox",
			//'onclick' => "adjust{$method}('{$method}personal');",
			'value' => 1,
			'default' => false,
			'checked' => $checked,
			'class' => 'elgg-subscriptions-toggle',
			'data-method' => $method,
		]);

		$link = elgg_view('output/url', [
			'id' => "{$method}personal",
			'class' => $checked ? "{$method}toggleOn elgg-state-active" : "{$method}toggleOff elgg-state-inactive",
			//'onclick' => "adjust{$method}_alt('{$method}personal')",
			'text' => $checkbox,
			'href' => false,
		]);

		echo elgg_format_element('td', [
			'class' => "{$method}togglefield elgg-subscriptions-toggle-cell",
		], $link);
	}

	echo elgg_format_element('td', [], '&nbsp;');
	?>
</tr>
