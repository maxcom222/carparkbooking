<?php
include dirname(__FILE__) . '/elements/progress.php';
$STORAGE = &$_SESSION[$controller->defaultInstaller];
?>
<div class="i-wrap">
	<?php
	$hasErrors = FALSE;
	if (isset($_GET['err']) && !empty($_GET['err']) && isset($_SESSION[$controller->defaultErrors][$_GET['err']]))
	{
		?>
		<div class="i-status i-status-error">
			<div class="i-status-icon"><abbr></abbr></div>
			<div class="i-status-txt">
				<h2>Installation error!</h2>
				<p class="t10"><?php echo @$_SESSION[$controller->defaultErrors][$_GET['err']]; ?></p>
			</div>
		</div>
		<?php
		$hasErrors = TRUE;
		$alert = array('status' => 'ERR', 'text' => strip_tags($_SESSION[$controller->defaultErrors][$_GET['err']]));
	}
	?>
	
	<p>Enter login details for product administration page. Once product is installed and you log in the administration page you will be able to change these details.</p>
	
	<form action="index.php?controller=pjInstaller&amp;action=pjActionStep6&amp;install=1" method="post" id="frmStep5" class="i-form">
		<input type="hidden" name="step5" value="1" />

		<table cellpadding="0" cellspacing="0" class="i-table t20">
		  <thead>
				<tr>
					<th>Administrator Login</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>
							<label class="i-title"><span class="i-red">*</span> E-Mail</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
								<input type="text" tabindex="1" name="admin_email" id="admin_email" class="pj-form-field w200" value="<?php echo isset($STORAGE['admin_email']) ? htmlspecialchars($STORAGE['admin_email']) : NULL; ?>" />
							</span>
						</p>
						<p>
							<label class="i-title"><span class="i-red">*</span> Password</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>
								<input type="text" tabindex="2" name="admin_password" id="admin_password" class="pj-form-field w200" value="<?php echo isset($STORAGE['admin_password']) ? htmlspecialchars($STORAGE['admin_password']) : NULL; ?>" />
							</span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div class="t20">
			<p class="float_left pt5">Need help? <a href="https://www.phpjabbers.com/contact.php" target="_blank">Contact us</a></p>
			<input type="submit" tabindex="3" value="Continue &raquo;" class="pj-button float_right l5" />
			<input type="button" tabindex="4" value="&laquo; Back" class="pj-button float_right" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep4'" />
			<br class="clear_both" />
		</div>
	</form>
</div>
<?php 
if ($hasErrors)
{
	?><img src="https://www.stivasoft.com/trackInstall.php?version=<?php echo PJ_SCRIPT_VERSION; ?>&build=<?php echo PJ_SCRIPT_BUILD; ?>&script=<?php echo PJ_SCRIPT_ID; ?>&license_key=<?php echo urlencode(@$_SESSION[$controller->defaultInstaller]['license_key']); ?>&alert=<?php echo urlencode(base64_encode(serialize($alert))); ?>" style="display: none" /><?php
}
?>