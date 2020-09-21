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
	pjUtil::printNotice(__('infoTermsTitle', true), __('infoTermsDesc', true));
	
	if (isset($tpl['arr']))
	{
		if (is_array($tpl['arr']))
		{
			$count = count($tpl['arr']) - 1;
			if ($count > 0)
			{
				?>
				<?php
				$locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : NULL;
				if (is_null($locale))
				{
					foreach ($tpl['lp_arr'] as $v)
					{
						if ($v['is_default'] == 1)
						{
							$locale = $v['id'];
							break;
						}
					}
				}
				if (is_null($locale))
				{
					$locale = @$tpl['lp_arr'][0]['id'];
				}
				?>
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
				<br/><br/>
				<div class="clear_both">
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form">
						<input type="hidden" name="options_update" value="1" />
						<input type="hidden" name="next_action" value="pjActionTerm" />
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
						if ($tpl['arr'][$i]['tab_id'] != 7 || (int) $tpl['arr'][$i]['is_visible'] === 0) continue;
						
						$rowClass = NULL;
						$rowStyle = NULL;
						
						
						?>
						<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
							<td width="35%" valign="top">
								<span class="block bold " ><?php __('opt_' . $tpl['arr'][$i]['key']); ?></span>
								<span class="fs10"><?php nl2br(__('opt_' . $tpl['arr'][$i]['key'].'_text')); ?></span>
							</td>
							<td>
								<?php
								switch ($tpl['arr'][$i]['type'])
								{
									case 'string':
										if(in_array($tpl['arr'][$i]['key'], array('o_email_confirmation_subject','o_email_payment_subject','o_email_cancel_subject')))
										{
										?>
											<?php
												foreach ($tpl['lp_arr'] as $v)
												{
													?>
													<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
														<span class="inline_block">
															<input type="text" name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field w400" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?>" />
															<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
															<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
															<?php endif; ?>
														</span>
													</p>
													<?php
												}
											?>
										<?php
										}
										else { ?>
											<input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field w400" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" />
										<?php }
										break;
									case 'text':
										
											foreach ($tpl['lp_arr'] as $v)
											{
												?>
												<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
													<span class="inline_block">
														<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field" style="width: 400px; height: 400px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
														<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
														<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
														<?php endif; ?>
													</span>
												</p>
												<?php
											}
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
											if ($default[1] == $el)
											{
												?><option value="<?php echo $default[0].'::'.$el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
											} else {
												?><option value="<?php echo $default[0].'::'.$el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
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
				</div>	
				
				
				<?php
			}
		}
	}
}
?>
<script type="text/javascript">
(function ($) {
$(function() {
	$(".multilang").multilang({
		langs: <?php echo $tpl['locale_str']; ?>,
		flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
		select: function (event, ui) {
			
		}
	});
	$(".multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
});
})(jQuery_1_8_2);
</script>