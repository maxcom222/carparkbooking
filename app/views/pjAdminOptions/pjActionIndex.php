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
	
	pjUtil::printNotice(__('infoGeneralTitle', true), __('infoGeneralDesc', true));
	
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
					<input type="hidden" name="next_action" value="pjActionIndex" />
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
					if ($tpl['arr'][$i]['tab_id'] != 1 || (int) $tpl['arr'][$i]['is_visible'] === 0) continue;
					$rowClass = NULL;
					$rowStyle = NULL;
					if (in_array($tpl['arr'][$i]['key'], array('o_smtp_host', 'o_smtp_port', 'o_smtp_user', 'o_smtp_pass')))
					{
						$rowClass = " boxSmtp";
						$rowStyle = "display: none";
						switch ($tpl['option_arr']['o_send_email'])
						{
							case 'smtp':
								$rowStyle = NULL;
								break;
						}
					}
					?>
					<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
						<td><?php __('opt_' . $tpl['arr'][$i]['key']); ?></td>
						<td>
							<?php
							$days = __('days', true);
							$enum_arr = __('enum_arr', true);
							switch ($tpl['arr'][$i]['type'])
							{
								case 'string':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field w200" value="<?php echo pjSanitize::html($tpl['arr'][$i]['value']); ?>" /><?php
									break;
								case 'text':
									?><textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 400px; height: 80px;"><?php echo pjSanitize::html($tpl['arr'][$i]['value']); ?></textarea><?php
									break;
								case 'int':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field field-int w60" value="<?php echo pjSanitize::html($tpl['arr'][$i]['value']); ?>" />&nbsp;<?php
									if($tpl['arr'][$i]['key'] == 'o_show_upto')
									{
										__('lblDays');
									}
									break;
								case 'float':
									?><input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field field-float w60" value="<?php echo pjSanitize::html($tpl['arr'][$i]['value']); ?>" /><?php
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
										switch ($tpl['arr'][$i]['key']) {
											case 'o_week_start':
												?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($days[$el]) ? $days[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
											break;
											case 'o_timezone':
												?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)) ?></option><?php
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
					
					<p><input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" /></p>
				</form>
				
				<?php
			}
		}
	}
}
?>