<div class="login-box">
	
	<h3><?php __('adminForgot'); ?></h3>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionForgot" method="post" id="frmForgotAdmin" class="form">
		<input type="hidden" name="forgot_user" value="1" />
		<p>
			<label class="title"><?php __('email'); ?>:</label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
				<input type="text" name="forgot_email" id="forgot_email" class="pj-form-field required email w250" />
			</span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSend', false, true); ?>" class="pj-button" />
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogin" class="no-decor l10"><?php __('lnkBack'); ?></a>
		</p>
		<?php
		if (isset($_GET['err']))
		{
			$titles = __('error_titles', true);
			$bodies = __('error_bodies', true);
			?>
			<em><label class="err"><?php echo @$titles[$_GET['err']]; ?><br><?php echo @$bodies[$_GET['err']]; ?></label></em>
			<?php
		}
		?>
	</form>
</div>