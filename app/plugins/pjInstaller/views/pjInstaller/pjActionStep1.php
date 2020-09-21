<?php
include dirname(__FILE__) . '/elements/progress.php';
$STORAGE = @$_SESSION[$controller->defaultInstaller];
$missing = $warning = array();
if (!PJ_DISABLE_MYSQL_CHECK && !$tpl['mysql_check'])
{
	$warning[] = 'MySQL database is not detected.';
}
if (!$tpl['session_check'])
{
	$missing[] = 'PHP SESSION does not work for your hosting account. Please, contact your hosting company and ask them to fix it.';
}
if (!$tpl['folder_check'])
{
	$missing = array_merge($missing, $tpl['folder_arr']);
}
if (!$tpl['dependencies_check'])
{
	$missing = array_merge($missing, $tpl['dependencies_arr']);
}
?>
<div class="i-wrap">
	<?php
	$title = count($missing) > 0 ? 'Installation error!' : 'Warning!';
	$notices = array_merge($missing, $warning);
	$hasErrors = !empty($notices);
	if ($hasErrors)
	{
		?>
		<div class="i-status i-status-error">
			<div class="i-status-icon"><abbr></abbr></div>
			<div class="i-status-txt">
				<h2><?php echo $title; ?></h2>
				<?php
				foreach ($notices as $item)
				{
					?><p class="t10"><?php echo $item; ?></p><?php
				}
				?>
			</div>
		</div>
		<?php
		$alert = array('status' => 'ERR', 'text' => join("\n", array_map('strip_tags', $notices)));
	}
	?>
	<p>Bellow you can see server software required to install our product. This is server based software and should be supported by your hosting company. If any of the software below is not supported you should contact your hosting company and ask them to upgrade your hosting plan.</p>
	
	<form action="index.php?controller=pjInstaller&amp;action=pjActionStep2&amp;install=1" method="post" id="frmStep1" class="i-form">
		<input type="hidden" name="step1" value="1" />
	
		<table cellpadding="0" cellspacing="0" class="i-table t20">
			<thead>
				<tr>
					<th>Check</th>
					<th style="width: 20%">&nbsp;</th>
					<th style="width: 20%">Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="bold">PHP</td>
					<td><span class="bold">5.1.0</span><input type="hidden" name="php_version" value="<?php echo $tpl['php_check'] === true ? 1 : 0; ?>" /></td>
					<td><span class="i-option i-option-<?php echo $tpl['php_check'] === true ? 'ok' : 'err'; ?>"></span></td>
				</tr>
				<?php if (!PJ_DISABLE_MYSQL_CHECK) : ?>
				<tr>
					<td class="bold">MySQL</td>
					<td><span class="bold">5.0</span><input type="hidden" name="mysql_version" value="<?php echo $tpl['mysql_check'] === true ? 1 : 0; ?>" /></td>
					<td><span class="i-option i-option-<?php echo $tpl['mysql_check'] === true ? 'ok' : 'err'; ?>"></span></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="bold">PHP Sessions</td>
					<td><span class="bold">&nbsp;</span><input type="hidden" name="php_session" value="<?php echo $tpl['session_check'] === true ? 1 : 0; ?>" /></td>
					<td><span class="i-option i-option-<?php echo $tpl['session_check'] === true ? 'ok' : 'err'; ?>"></span></td>
				</tr>
				<tr>
					<td class="bold">Dependencies</td>
					<td><span class="bold">&nbsp;</span><input type="hidden" name="php_session" value="<?php echo $tpl['dependencies_check'] === true ? 1 : 0; ?>" /></td>
					<td><span class="i-option i-option-<?php echo $tpl['dependencies_check'] === true ? 'ok' : 'err'; ?>"></span></td>
				</tr>
			</tbody>
		</table>
		
		<div class="t20">
			<p class="float_left pt5">Need help? <a href="https://www.phpjabbers.com/contact.php" target="_blank">Contact us</a></p>
			<?php if (count($missing) === 0) : ?>
			<input type="submit" tabindex="1" value="Continue &raquo;" class="pj-button float_right" />
			<?php endif; ?>
			<br class="clear_both" />
		</div>
	</form>
</div>