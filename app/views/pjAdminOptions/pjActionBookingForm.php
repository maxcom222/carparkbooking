<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
		
	pjUtil::printNotice(__('infoBookingFormTitle', true), __('infoBookingFormDesc', true));
	
	if (isset($tpl['arr']))
	{
		if (is_array($tpl['arr']))
		{
			$count = count($tpl['arr']);
			if ($count > 0)
			{
				?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form">
					<input type="hidden" name="options_update" value="1" />
					<input type="hidden" name="next_action" value="pjActionBookingForm" />
					<table class="pj-table" cellpadding="0" cellspacing="0" style="width: 100%">
						<thead>
							<tr>
								<th><?php __('lblOption'); ?></th>
								<th><?php __('lblValue'); ?></th>
							</tr>
						</thead>
						<tbody>
	
				<?php
				for ($i = 0; $i < $count; $i++)
				{
					if ($tpl['arr'][$i]['tab_id'] != 5 || (int) $tpl['arr'][$i]['is_visible'] === 0) continue;
					
					?>
					<tr class="pj-table-row-odd">
						<td width="30%">
							<?php __('opt_' . $tpl['arr'][$i]['key']); ?>
						</td>
						<td>
							<?php
							$enum_arr = __('enum_arr', true);
							switch ($tpl['arr'][$i]['type'])
							{
								case 'string':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field w200" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" /><?php
									break;
								case 'text':
									?><textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 400px; height: 80px;"><?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?></textarea><?php
									break;
								case 'int':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field field-int w60" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" /><?php
									break;
								case 'float':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field field-float w60" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" /><?php
									break;
								case 'enum':
									?><select name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field">
									<?php
									$default = explode("::", $tpl['arr'][$i]['value']);
									$enum = explode("|", $default[0]);
									
									$enumLabels = array();
									if (!empty($tpl['arr'][$i]['label']) && strpos($tpl['arr'][$i]['label'], "|") !== false)
									{
										$enumLabels = explode("|", $tpl['arr'][$i]['label']);
									}
									
									foreach ($enum as $k => $el)
									{
										switch ($tpl['arr'][$i]['value']) {
											case '1|2|3::3':
											case '1|2|3::2':
											case '1|2|3::1':
												?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el-1]) ? $enum_arr[$el-1] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
											break;
											
											default:
												?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
											break;
										}
									}
									?>
									</select>
									<?php
									break;
							}
							?>
						</td>
					</tr>
					<?php
				}
				?>
						</tbody>
					</table>
					
					<p><input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" /></p>
				</form>
				
				<?php
			}
		}
	}
}
?>