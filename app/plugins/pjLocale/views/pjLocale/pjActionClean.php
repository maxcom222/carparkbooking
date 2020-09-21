<fieldset class="fieldset white">
	<legend>Step 1. Translations for non-existed labels</legend>
	<?php
	if (isset($tpl['step1_arr']) && !empty($tpl['step1_arr']))
	{
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionClean" method="post" class="pj-form">
			<input type="hidden" name="clean_step" value="1" />
			<p><?php printf("Total: %u records found.<br><br><br>", count($tpl['step1_arr'])); ?></p>
			<input type="submit" class="pj-button" value="Delete" />
		</form>
		<?php
	} else {
		?><p>No records found.</p><?php
	}
	?>
</fieldset>

<fieldset class="fieldset white">
	<legend>Step 2. Non-used labels</legend>
	<?php
	if (isset($tpl['field_arr']) && !empty($tpl['field_arr']))
	{
		?>
		<p class="b10">WARNING! Please review the list carefully before take an action!
		<br>Total records: <span class="bold"><?php echo count($tpl['field_arr']); ?></span>
		<br>Not every record here is necessarily useless, it depends on the way it is used.
		<br>This list is test against next Regular Expression: /__\(\s*\'(\w+)\'\s*(?:,\s*(?i:true|false))?\)/i
		<br>which satisfy: __('some_key_here') and __('some_key_here', true)
		<br>but not: __('some_key_' . $some_var)
		</p>
		
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionClean" method="post" class="pj-form">
			<input type="hidden" name="clean_step" value="2" />
			<table cellpadding="0" cellspacing="0" class="pj-table b10" style="width: 100%">
				<thead>
					<tr>
						<th class="w20"><input type="checkbox" name="toggle" value="1" /></th>
						<th>Key</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($tpl['field_arr'] as $field)
				{
					$skip = $field['type'] == 'arrays' || strpos($field['key'], 'opt_') === 0;
					?>
					<tr class="<?php echo !$skip ? 'pj-table-row-odd' : 'pj-table-row-even'; ?>">
						<td><input type="checkbox" name="field_id[]" value="<?php echo $field['id']; ?>" /></td>
						<td><?php echo $field['key']; ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<input type="submit" class="pj-button" value="Delete" />
		</form>
		<?php
	} else {
		?><p>No records found.</p><?php
	}
	?>
</fieldset>