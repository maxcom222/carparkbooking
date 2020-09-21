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
	if (isset($tpl['warning']))
	{
		?>
		<div class="i-status i-status-error">
			<div class="i-status-icon"><abbr></abbr></div>
			<div class="i-status-txt">
				<h2>Warning!</h2>
				<p class="t10">If you proceed with the installation your current database tables and all the data will be deleted.</p>
			</div>
		</div>
		<?php
	}
	?>
	<p>We've detected the following server paths where product is uploaded. Most probably you will not have to change these paths.</p>
	
	<form action="index.php?controller=pjInstaller&amp;action=pjActionStep5&amp;install=1" method="post" id="frmStep4" class="i-form">
		<input type="hidden" name="step4" value="1" />
		<table cellpadding="0" cellspacing="0" class="i-table t20">
			<thead>
				<tr>
					<th>Installation paths</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>
							<label class="i-title">Folder Name <span class="i-red">*</span></label>
							<input type="text" tabindex="1" name="install_folder" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_folder'] : htmlspecialchars(@$STORAGE['install_folder']); ?>" /></p>
						<p>
							<label class="i-title">Full URL <span class="i-red">*</span></label>
							<input type="text" tabindex="2" name="install_url" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_url'] : htmlspecialchars(@$STORAGE['install_url']); ?>" />
						</p>
						<p>
							<label class="i-title">Server Path <span class="i-red">*</span></label>
							<input type="text" tabindex="3" name="install_path" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_path'] : htmlspecialchars(@$STORAGE['install_path']); ?>" />
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div class="t20">
			<p class="float_left pt5">Need help? <a href="https://www.phpjabbers.com/contact.php" target="_blank">Contact us</a></p>
			<input type="submit" tabindex="4" value="Continue &raquo;" class="pj-button float_right l5" />
			<input type="button" tabindex="5" value="&laquo; Back" class="pj-button float_right" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep3'" />
			<br class="clear_both" />
		</div>
	</form>
	
	<div class="i-status i-status-notice">
		<div class="i-status-icon"><span class="bold block t15 l15">Examples:</span></div>
		<div class="i-status-txt">
			<p class="float_left"># if the product is uploaded in http://www.website.com/script/ then<br />Folder name should be: /script/<br />Full URL should be: http://website.com/script/</p>
			<p class="float_right"># if the product is uploaded in http://website.com/folder/script/ then<br />Folder name should be: /folder/script/<br />Full URL should be: http://website.com/folder/script/</p>
		</div>
	</div>
</div>
<?php 
if ($hasErrors)
{
	?><img src="https://www.stivasoft.com/trackInstall.php?version=<?php echo PJ_SCRIPT_VERSION; ?>&build=<?php echo PJ_SCRIPT_BUILD; ?>&script=<?php echo PJ_SCRIPT_ID; ?>&license_key=<?php echo urlencode(@$_SESSION[$controller->defaultInstaller]['license_key']); ?>&alert=<?php echo urlencode(base64_encode(serialize($alert))); ?>" style="display: none" /><?php
}
?>