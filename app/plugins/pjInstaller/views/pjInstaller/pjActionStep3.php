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
		
	<p>Please enter MYSQL login details for your server. If you do not know these please contact your hosting company and ask them to provide you with correct details.</p>
	<p>Alternatively, you can send us access to your hosting account control panel (the place where you manage your hosting account) and we can create MySQL database and user for you.</p>
	
	<form action="index.php?controller=pjInstaller&amp;action=pjActionStep4&amp;install=1" method="post" id="frmStep3" class="i-form">
		<input type="hidden" name="step3" value="1" />
		<table cellpadding="0" cellspacing="0" class="i-table t20">
			<thead>
				<tr>
					<th>MySQL Login Details</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p>
							<label class="i-title">Hostname <span class="i-red">*</span></label>
							<input type="text" tabindex="1" name="hostname" class="pj-form-field w200 required block" value="<?php echo isset($STORAGE['hostname']) ? htmlspecialchars($STORAGE['hostname']) : 'localhost'; ?>" />
							<span style="display: block;text-indent: 120px;">*Hostname could be hostname (domain or localhost) or IP address. You can also specify specific server port example.com:3307 or socket :/tmp/mysql</span>
						</p>
						<p>
							<label class="i-title">Username <span class="i-red">*</span></label>
							<input type="text" tabindex="2" name="username" class="pj-form-field w200 required" value="<?php echo isset($STORAGE['username']) ? htmlspecialchars($STORAGE['username']) : NULL; ?>" />
						</p>
						<p>
							<label class="i-title">Password</label>
							<input type="text" tabindex="3" name="password" class="pj-form-field w200" value="<?php echo isset($STORAGE['password']) ? htmlspecialchars($STORAGE['password']) : NULL; ?>" />
						</p>
						<p>
							<label class="i-title">Database <span class="i-red">*</span></label>
							<input type="text" tabindex="4" name="database" class="pj-form-field w200 required" value="<?php echo isset($STORAGE['database']) ? htmlspecialchars($STORAGE['database']) : NULL; ?>" />
						</p>
						<p>
							<label class="i-title">Table prefix</label>
							<input type="text" tabindex="5" name="prefix" class="pj-form-field w200" value="<?php echo isset($STORAGE['prefix']) ? htmlspecialchars($STORAGE['prefix']) : NULL; ?>" />
							<span style="display: block;text-indent: 120px;">* you can leave that blank or enter table prefix which will be added to all MySQL tables names</span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	
		<div class="t20">
			<p class="float_left pt5">Need help? <a href="https://www.phpjabbers.com/contact.php" target="_blank">Contact us</a></p>
			<input type="submit" tabindex="6" value="Continue &raquo;" class="pj-button float_right l5" />
			<input type="button" tabindex="7" value="&laquo; Back" class="pj-button float_right" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep2'" />
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