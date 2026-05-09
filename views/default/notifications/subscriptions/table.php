<?php
$methods = array_keys(_elgg_services()->notifications->getMethods());
?>
<table id="notificationstable" class="elgg-subscriptions-table">
	<thead>
		<tr>
			<th class="namefield elgg-subscriptions-type-label"></th>
				<?php
				foreach ($methods as $method) {
					echo elgg_format_element('th', [
						'class' => $method ? "{$method}togglefield elgg-subscriptions-toggle-cell" : 'elgg-subscriptions-toggle-cell',
					], elgg_echo("notification:method:$method"));
				}
				?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
		echo elgg_extract('rows', $vars);
		?>
	</tbody>
</table>
