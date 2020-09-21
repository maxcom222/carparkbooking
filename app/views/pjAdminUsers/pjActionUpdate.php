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
	
	pjUtil::printNotice(__('infoUpdateUserTitle', true, false), __('infoUpdateUserDesc', true, false));
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate" method="post" id="frmUpdateUser" class="form pj-form">
		<input type="hidden" name="user_update" value="1" />
		<input type="hidden" name="id" value="<?php echo (int) $tpl['arr']['id']; ?>" />
		<p>
			<label class="title"><?php __('lblRole'); ?></label>
			<?php
			if ((int) $tpl['arr']['id'] !== 1)
			{
				?>
				<span class="inline_block">
					<select name="role_id" id="role_id" class="pj-form-field required">
						<option value="">-- <?php __('lblChoose'); ?>--</option>
						<?php
						foreach ($tpl['role_arr'] as $v)
						{
							?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['role_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['role']); ?></option><?php
						}
						?>
					</select>
				</span>
				<?php
			} else {
				?>
				<span class="left">
				<?php
				foreach ($tpl['role_arr'] as $v)
				{
					if ($tpl['arr']['role_id'] == $v['id'])
					{
						echo stripslashes($v['role']);
						break;
					}
				}
				?>
				</span>
				<input type="hidden" name="role_id" value="1" />
				<?php
			}
			?>
		</p>
		<p>
			<label class="title"><?php __('email'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
				<input type="text" name="email" id="email" class="pj-form-field required email w200" value="<?php echo pjSanitize::html($tpl['arr']['email']); ?>" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('pass'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>
				<input type="text" name="password" id="password" class="pj-form-field required w200" value="<?php echo pjSanitize::html($tpl['arr']['password']); ?>" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblName'); ?></label>
			<span class="inline_block">
				<input type="text" name="name" id="name" value="<?php echo pjSanitize::html($tpl['arr']['name']); ?>" class="pj-form-field w250 required" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblPhone'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
				<input type="text" name="phone" id="phone" value="<?php echo pjSanitize::html($tpl['arr']['phone']); ?>" class="pj-form-field w200" placeholder="(123) 456-7890"/>
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblStatus'); ?></label>
			<?php
			if ((int) $tpl['arr']['id'] !== 1)
			{
				?>
				<span class="inline_block">
					<select name="status" id="status" class="pj-form-field required">
						<option value="">-- <?php __('lblChoose'); ?>--</option>
						<?php
						foreach (__('u_statarr', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['status'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</span>
				<?php
			} else {
				$status = __('u_statarr', true)
				?>
				<span class="left"><?php echo @$status[$tpl['arr']['status']]; ?></span>
				<input type="hidden" name="status" value="T" />
				<?php
			}
			?>
		</p>
		<p>
			<label class="title"><?php __('lblUserCreated'); ?></label>
			<span class="left"><?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['created'])); ?>, <?php echo date("H:i", strtotime($tpl['arr']['created'])); ?></span>
		</p>
		<p>
			<label class="title"><?php __('lblIp'); ?></label>
			<span class="left"><?php echo $tpl['arr']['ip']; ?></span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
			<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminUsers&action=pjActionIndex';" />
		</p>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.email_taken = "<?php __('pj_email_taken', false, true); ?>";
	</script>
	<?php
}
?>