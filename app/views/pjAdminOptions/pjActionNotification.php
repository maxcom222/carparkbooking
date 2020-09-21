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
				$enum_arr = __('enum_arr', true);
				?>
		
				<div class="clear_both">
					<form id="frmNotification" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form">
						<input type="hidden" name="options_update" value="1" />
						<input type="hidden" name="next_action" value="pjActionNotification" />
						<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
						
						<div id="tabs">
							<ul>
								<li><a href="#tabs-1"><?php __('lblToCustomers');?></a></li>
								<li><a href="#tabs-2"><?php __('lblToAdministrators');?></a></li>
							</ul>
							<div id="tabs-1">
								<?php
								pjUtil::printNotice(__('infoToCustomersTitle', true), __('infoToCustomersDesc', true) . '<br/><br/>'  . __('lblAvailableTokens', true), false); 
								?>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<div class="multilang"></div>
								<?php endif; ?>
								<br/><br/>
								
								<fieldset class="fieldset white">
									<legend><?php __('lblLegendEmails');?></legend>
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
												if ($tpl['arr'][$i]['tab_id'] == 3 && (int) $tpl['arr'][$i]['is_visible'] === 1 && (strpos($tpl['arr'][$i]['key'], 'admin') === false) && (strpos($tpl['arr'][$i]['key'], 'email') > -1))
												{
												
													$rowClass = NULL;
													$rowStyle = NULL;
													if (in_array($tpl['arr'][$i]['key'], array('o_email_confirmation_subject', 'o_email_confirmation_message')))
													{
														$rowClass = " boxClientConfirmation";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_email_confirmation'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													if (in_array($tpl['arr'][$i]['key'], array('o_email_payment_subject', 'o_email_payment_message')))
													{
														$rowClass = " boxClientPayment";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_email_payment'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													if (in_array($tpl['arr'][$i]['key'], array('o_email_cancel_subject', 'o_email_cancel_message')))
													{
														$rowClass = " boxClientCancel";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_email_cancel'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													
													?>
													<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
														<td width="35%" valign="top">
															<span class="block bold"><?php __('opt_' . $tpl['arr'][$i]['key']); ?></span>
															<?php
															if (!in_array($tpl['arr'][$i]['key'], array('o_email_confirmation_message', 'o_email_payment_message', 'o_email_cancel_message')))
															{
																?>
																<span class="fs10"><?php echo str_replace(array('\r\n','\n\r','\n','\r',"\r\n","\n\r","\n","\r"),'<br />',__('opt_' . $tpl['arr'][$i]['key'].'_text', true)); ?></span>
																<?php
															} 
															?>
														</td>
														<td valign="top">
															<?php
															switch ($tpl['arr'][$i]['type'])
															{
																case 'string':
																	if(in_array($tpl['arr'][$i]['key'], array('o_email_confirmation_subject','o_email_payment_subject','o_email_cancel_subject', 'o_email_account_subject', 'o_email_forgot_subject')))
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
																	
																	if(in_array($tpl['arr'][$i]['key'], array('o_email_confirmation_message','o_email_payment_message','o_email_cancel_message', 'o_email_account_message', 'o_email_forgot_message')))
																	{
																	?>
																		<?php
																			foreach ($tpl['lp_arr'] as $v)
																			{
																				?>
																				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
																					<span class="inline_block">
																						<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field mceEditor" style="width: 400px; height: 500px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
																						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
																						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
																						<?php endif;?>
																					</span>
																				</p>
																				<?php
																			}
																		?>
																	<?php
																	}
																	else { ?>
																		<textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 460px; height: 400px;"><?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?></textarea>
																	<?php }
																			
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
																		?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
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
											}
											?>
										</tbody>
									</table>
								</fieldset>
								<fieldset class="fieldset white">
									<legend><?php __('lblLegendSMS');?></legend>
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
												if ($tpl['arr'][$i]['tab_id'] == 3 && (int) $tpl['arr'][$i]['is_visible'] === 1 && (strpos($tpl['arr'][$i]['key'], 'admin') === false) && (strpos($tpl['arr'][$i]['key'], 'sms') > -1))
												{
												
													$rowClass = NULL;
													$rowStyle = NULL;
											
													?>
													<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
														<td width="35%" valign="top">
															<span class="block bold"><?php __('opt_' . $tpl['arr'][$i]['key']); ?></span>
															<span class="fs10"><?php echo str_replace(array('\r\n','\n\r','\n','\r',"\r\n","\n\r","\n","\r"),'<br />',__('opt_' . $tpl['arr'][$i]['key'].'_text', true)); ?></span>
														</td>
														<td valign="top">
															<?php
															switch ($tpl['arr'][$i]['type'])
															{
																case 'string':
																	?>
																		<input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field w400" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" />
																	<?php 
																	break;
																case 'text':
																	
																	if(in_array($tpl['arr'][$i]['key'], array('o_sms_confirmation_message','o_sms_payment_message','o_sms_cancel_message')))
																	{
																	?>
																		<?php
																			foreach ($tpl['lp_arr'] as $v)
																			{
																				?>
																				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
																					<span class="inline_block">
																						<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field" style="width: 400px; height: 200px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
																						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
																					</span>
																				</p>
																				<?php
																			}
																		?>
																	<?php
																	}
																	else { ?>
																		<textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 460px; height: 500px;"><?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?></textarea>
																	<?php }
																			
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
																		?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
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
											}
											?>
										</tbody>
									</table>
								</fieldset>
								<p><input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" /></p>
							</div><!-- tabs-1 -->
							
							<div id="tabs-2">
								<?php
								pjUtil::printNotice(__('infoToAdministratorsTitle', true), __('infoToAdministratorsDesc', true) . '<br/><br/>'  . __('lblAvailableTokens', true), false); 
								?>
								<div class="multilang"></div>
								<br/><br/>
								<fieldset class="fieldset white">
									<legend><?php __('lblLegendEmails');?></legend>
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
												if (($tpl['arr'][$i]['tab_id'] == 3 && (int) $tpl['arr'][$i]['is_visible'] === 1 && strpos($tpl['arr'][$i]['key'], 'admin') >-1 && (strpos($tpl['arr'][$i]['key'], 'email') > -1)))
												{											
													$rowClass = NULL;
													$rowStyle = NULL;
													if (in_array($tpl['arr'][$i]['key'], array('o_admin_email_confirmation_subject', 'o_admin_email_confirmation_message')))
													{
														$rowClass = " boxAdminConfirmation";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_admin_email_confirmation'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													if (in_array($tpl['arr'][$i]['key'], array('o_admin_email_payment_subject', 'o_admin_email_payment_message')))
													{
														$rowClass = " boxAdminPayment";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_admin_email_payment'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													if (in_array($tpl['arr'][$i]['key'], array('o_admin_email_cancel_subject', 'o_admin_email_cancel_message')))
													{
														$rowClass = " boxAdminCancel";
														$rowStyle = "display: none";
														switch ($tpl['option_arr']['o_admin_email_cancel'])
														{
															case '1':
																$rowStyle = NULL;
																break;
														}
													}
													$key = ( !in_array($tpl['arr'][$i]['key'], array('o_admin_email_confirmation', 'o_admin_email_payment', 'o_admin_email_cancel')) ? str_replace('admin_', '', $tpl['arr'][$i]['key']) : $tpl['arr'][$i]['key']);
													?>
													<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
														<td width="35%" valign="top">
															<span class="block bold"><?php __('opt_' . str_replace('admin_', '', $tpl['arr'][$i]['key'])); ?></span>
															<?php
															if (!in_array($tpl['arr'][$i]['key'], array('o_admin_email_confirmation_message', 'o_admin_email_payment_message', 'o_admin_email_cancel_message')))
															{ 
																?>
																<span class="fs10"><?php echo str_replace(array('\r\n','\n\r','\n','\r',"\r\n","\n\r","\n","\r"),'<br />',__('opt_' . $key . '_text', true, false)); ?></span>
																<?php
															} 
															?>
														</td>
														<td valign="top">
															<?php
															switch ($tpl['arr'][$i]['type'])
															{
																case 'string':
																	if(in_array($tpl['arr'][$i]['key'], array('o_admin_email_confirmation_subject','o_admin_email_payment_subject','o_admin_email_cancel_subject')))
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
																	
																	if(in_array($tpl['arr'][$i]['key'], array('o_admin_email_confirmation_message','o_admin_email_payment_message','o_admin_email_cancel_message')))
																	{
																	?>
																		<?php
																			foreach ($tpl['lp_arr'] as $v)
																			{
																				?>
																				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
																					<span class="inline_block">
																						<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field mceEditor" style="width: 400px; height: 500px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
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
																		<textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 460px; height: 400px;"><?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?></textarea>
																	<?php }
																			
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
																		?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
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
											}
											?>
										</tbody>
									</table>
								</fieldset>
								<fieldset class="fieldset white">
									<legend><?php __('lblLegendSMS');?></legend>
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
												if (($tpl['arr'][$i]['tab_id'] == 3 && (int) $tpl['arr'][$i]['is_visible'] === 1 && strpos($tpl['arr'][$i]['key'], 'admin') >-1 && (strpos($tpl['arr'][$i]['key'], 'sms') > -1)))
												{											
													$rowClass = NULL;
													$rowStyle = NULL;
												
													?>
													<tr class="pj-table-row-odd<?php echo $rowClass; ?>" style="<?php echo $rowStyle; ?>">
														<td width="35%" valign="top">
															<span class="block bold"><?php __('opt_' . $tpl['arr'][$i]['key']); ?></span>
															<span class="fs10"><?php echo str_replace(array('\r\n','\n\r','\n','\r',"\r\n","\n\r","\n","\r"),'<br />',__('opt_' . $tpl['arr'][$i]['key'].'_text', true)); ?></span>
														</td>
														<td valign="top">
															<?php
															switch ($tpl['arr'][$i]['type'])
															{
																case 'string':
																	?>
																		<input type="text" name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field w400" value="<?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?>" />
																	<?php
																	break;
																case 'text':
																	
																	if(in_array($tpl['arr'][$i]['key'], array('o_admin_sms_confirmation_message','o_admin_sms_payment_message','o_admin_sms_cancel_message')))
																	{
																	?>
																		<?php
																			foreach ($tpl['lp_arr'] as $v)
																			{
																				?>
																				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
																					<span class="inline_block">
																						<textarea name="i18n[<?php echo $v['id']; ?>][<?php echo $tpl['arr'][$i]['key'] ?>]" class="pj-form-field" style="width: 400px; height: 200px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']][$tpl['arr'][$i]['key']])); ?></textarea>
																						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
																					</span>
																				</p>
																				<?php
																			}
																		?>
																	<?php
																	}
																	else { ?>
																		<textarea name="value-<?php echo $tpl['arr'][$i]['type']; ?>-<?php echo $tpl['arr'][$i]['key']; ?>" class="pj-form-field" style="width: 460px; height: 200px;"><?php echo htmlspecialchars(stripslashes($tpl['arr'][$i]['value'])); ?></textarea>
																	<?php }
																			
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
																		?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL;?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
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
											}
											?>
										</tbody>
									</table>
								</fieldset>
								<p><input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" /></p>
							</div><!-- tabs-2 -->
						</div><!-- #tabs -->
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

	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{		
		$tab_id = $_GET['tab_id'];
		$tab_id = $tab_id < 0 ? 0 : $tab_id; 
		?>$("#tabs").tabs("option", "selected", <?php echo str_replace("tabs-", "", $tab_id) - 1;?>);<?php
	}
	?>
});
})(jQuery_1_8_2);
</script>