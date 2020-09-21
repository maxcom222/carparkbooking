<div class="i-wrap">
<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			?>
			<div class="i-status i-status-error">
				<div class="i-status-icon"><abbr></abbr></div>
				<div class="i-status-txt">
					<h2>Configuration error</h2>
					<p class="t10">Product is not installed yet. If you need to install, please <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInstaller&amp;action=pjActionStep1&amp;install=1">click here</a>.</p>
				</div>
			</div>	
			<?php
			break;
		case 2:
			?>
			<div class="i-status i-status-error">
				<div class="i-status-icon"><abbr></abbr></div>
				<div class="i-status-txt">
					<h2>Configuration error</h2>
					<p class="t10">Installation path, folder and URL are the same.</p>
				</div>
			</div>			
			<?php
			break;
		case 3:
			$title = 'Authorization required';
			$description = 'To see this page you need to login.';
			if (isset($_GET['err']) && !empty($_GET['err']) && isset($_SESSION[$controller->defaultErrors][$_GET['err']]))
			{
				$err = $_SESSION[$controller->defaultErrors][$_GET['err']];
				if (isset($err['text']))
				{
					$title = 'Authorization status';
					$description = $err['text'];
				}
			}
			?>
			<div class="i-status i-status-error">
				<div class="i-status-icon"><abbr></abbr></div>
				<div class="i-status-txt">
					<h2><?php echo $title; ?></h2>
					<p class="t10"><?php echo $description; ?></p>
				</div>
			</div>
			<fieldset class="fieldset white">
				<legend>Login</legend>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInstaller&amp;action=pjActionChange" method="post" id="frmChangeLogin" class="i-form" autocomplete="off">
					<input type="hidden" name="do_login" value="1" />
					
					<p>
						<label class="i-title">Email: <span class="i-red">*</span></label>
						<span>
							<input type="text" name="email" class="pj-form-field w200" data-msg-required="Email address is required" data-msg-email="Valid email address is required" autocomplete="off" maxlength="255" />
						</span>
					</p>
					
					<p>
						<label class="i-title">Licence key: <span class="i-red">*</span></label>
						<span>
							<input type="text" name="license_key" class="pj-form-field w400" data-msg-required="Licence key is required" autocomplete="off" maxlength="255" />
						</span>
					</p>
					
					<p>
						<label class="i-title">Captcha: <span class="i-red">*</span></label>
						<span>
							<input type="text" name="captcha" class="pj-form-field w100" maxlength="6" data-msg-required="Captcha is required" data-msg-remote="Captcha doesn't match" autocomplete="off" />
							<img src="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInstaller&amp;action=pjActionCaptcha&amp;rand=<?php echo rand(1,9999); ?>" alt="Captcha" class="i-captcha" title="Click to reload" />
						</span>
					</p>
					
					<p>
						<label class="i-title">&nbsp;</label>
						<button type="submit" class="pj-button">Log In</button>
					</p>
				</form>
			</fieldset>
			<?php
			break;
	}
} else {
	if (isset($_GET['err']) && !empty($_GET['err']) && isset($_SESSION[$controller->defaultErrors][$_GET['err']]))
	{
		$err = $_SESSION[$controller->defaultErrors][$_GET['err']];
		switch ($err['status'])
		{
			case 'ERR':
				?>
				<div class="i-status i-status-error">
					<div class="i-status-icon"><abbr></abbr></div>
					<div class="i-status-txt">
						<h2>Change has not been successfull</h2>
						<p class="t10"><?php echo @$err['text']; ?></p>
					</div>
				</div>
				<?php
				break;
			case 'OK':
				?>
				<div class="i-status i-status-success">
					<div class="i-status-icon"><abbr></abbr></div>
					<div class="i-status-txt">
						<h2>Change has been successfull</h2>
						<p class="t10"><?php echo @$err['text']; ?></p>
					</div>
				</div>
				<?php
				break;
		}
	}
	?>
	<form action="" method="post" class="i-form" id="frmChange">
		<input type="hidden" name="do_change" value="1" />
		<input type="hidden" name="change_domain" value="0" />
		<input type="hidden" name="change_db" value="0" />
		<input type="hidden" name="change_paths" value="<?php echo (!$tpl['areTheSamePaths']) ? 1 : 0; ?>" />
		<fieldset class="fieldset white">
			<legend>Domain</legend>
			<div class="float_left w480">
				<p>
					<label class="i-title">Domain:</label>
					<span class="left float_left w300"><?php echo $tpl['domain']; ?> (<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="changeDomain">change</a>)</span>
				</p>
			</div>
			<div class="boxDomain float_right w480" style="display: none">
				<p>
					<label class="i-title">New domain: <span class="i-red">*</span></label>
					<input name="new_domain" class="pj-form-field w250" value="<?php echo pjSanitize::html($_SERVER['SERVER_NAME']); ?>" />
				</p>
				<p>
					<label class="i-title">License key: <span class="i-red">*</span></label>
					<input name="license_key" class="pj-form-field w250" />
				</p>
			</div>
			<br class="clear_both" />
		</fieldset>
		
		<fieldset class="fieldset white">
			<legend>Database</legend>
			<div class="float_left w480">
				<p><span class="bold">Current MySQL login details</span> (<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="changeMySQL">change</a>)</p>
				<p>
					<label class="i-title">Hostname:</label>
					<span class="left float_left w300"><?php echo PJ_HOST; ?></span>
				</p>
				<p>
					<label class="i-title">Username:</label>
					<span class="left float_left w300"><?php echo PJ_USER; ?></span>
				</p>
				<p>
					<label class="i-title">Password:</label>
					<span class="left float_left w300"><?php echo str_repeat('*', 6); ?></span>
				</p>
				<p>
					<label class="i-title">Database:</label>
					<span class="left float_left w300"><?php echo PJ_DB; ?></span>
				</p>
			</div>
			<div class="boxMySQL float_right w480" style="display: none">
				<p class="bold">New MySQL login details</p>
				<p>
					<label class="i-title">Hostname: <span class="i-red">*</span></label>
					<input name="hostname" class="pj-form-field w250" />
				</p>
				<p>
					<label class="i-title">Username: <span class="i-red">*</span></label>
					<input name="username" class="pj-form-field w250" />
				</p>
				<p>
					<label class="i-title">Password:</label>
					<input name="password" class="pj-form-field w250" />
				</p>
				<p>
					<label class="i-title">Database: <span class="i-red">*</span></label>
					<input name="database" class="pj-form-field w250" />
				</p>
			</div>
			<br class="clear_both" />
		</fieldset>
		
		<fieldset class="fieldset white">
			<legend>Paths</legend>
			<div class="float_left w480">
				<p class="bold">Installation path and URL in config.inc.php file</p>
				<?php
				if (!$tpl['areTheSamePaths'])
				{
					?><p class="italic">&nbsp;</p><?php
				}
				?>
				<p>
					<label class="i-title">Folder Name:</label>
					<span class="left float_left w300"><?php echo PJ_INSTALL_FOLDER; ?></span>
				</p>
				<p>
					<label class="i-title">Full URL:</label>
					<span class="left float_left w300"><?php echo PJ_INSTALL_URL; ?></span>
				</p>
				<p>
					<label class="i-title">Server Path:</label>
					<span class="left float_left w300"><?php echo PJ_INSTALL_PATH; ?></span>
				</p>
			</div>
			<?php 
			if (!$tpl['areTheSamePaths'])
			{
				?>
				<div class="boxPaths float_right w480">
					<p class="bold">Current path and URL</p>
					<p class="italic">(These will be set for your installation)</p>
					<p>
						<label class="i-title">Folder Name:</label>
						<span class="left float_left w300"><?php echo pjSanitize::html($tpl['paths']['install_folder']); ?></span>
					</p>
					<p>
						<label class="i-title">Full URL:</label>
						<span class="left float_left w300"><?php echo pjSanitize::html($tpl['paths']['install_url']); ?></span>
					</p>
					<p>
						<label class="i-title">Server Path:</label>
						<span class="left float_left w300"><?php echo pjSanitize::html($tpl['paths']['install_path']); ?></span>
					</p>
				</div>
				<?php 
			}
			?>
			<br class="clear_both" />
		</fieldset>
		
		<div class="t20">
			<p class="float_left">Need help? <a href="https://www.phpjabbers.com/contact.php" target="_blank">Contact us</a></p>
			<input type="submit" value="Update installation" class="pj-button float_right" />
			<br class="clear_both" />
		</div>
	</form>
	<?php
}
?>
</div>