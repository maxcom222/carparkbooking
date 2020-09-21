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
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	
	<?php pjUtil::printNotice(__('lblInstallJs1_title', true), __('lblInstallJs1_body', true), false, false); ?>

	<?php if (count($tpl['locale_arr']) > 1) : ?>
	<form action="" method="get" class="pj-form form">
		<fieldset class="fieldset white">
			<legend><?php __('lblInstallConfig'); ?></legend>
			<p>
				<label class="title"><?php __('lblInstallConfigLocale'); ?></label>
				<select class="pj-form-field w200" name="install_locale">
					<option value="">-- <?php __('lblChoose'); ?> --</option>
					<?php
					foreach ($tpl['locale_arr'] as $locale)
					{
						?><option value="<?php echo $locale['id']; ?>"><?php echo pjSanitize::html($locale['title']); ?></option><?php
					}
					?>
				</select>
			</p>
			<p>
				<label class="title"><?php __('lblInstallConfigHide'); ?></label>
				<span class="left">
					<input type="checkbox" name="install_hide" value="1" />
				</span>
			</p>
		</fieldset>
	</form>
	<?php endif; ?>
	
	<p style="margin: 20px 0 7px; font-weight: bold"><?php __('lblInstallJs1_1'); ?></p>
	<textarea class="pj-form-field textarea_install" id="install_code" style="overflow: auto; height:90px; width: 726px;">
&lt;link href="<?php echo PJ_INSTALL_URL.PJ_FRAMEWORK_LIBS_PATH . 'pj/css/'; ?>pj.bootstrap.min.css" type="text/css" rel="stylesheet" /&gt;
&lt;link href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoadCss" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoad"&gt;&lt;/script&gt;</textarea>

	<div style="display:none" id="hidden_code">&lt;link href="<?php echo PJ_INSTALL_URL.PJ_FRAMEWORK_LIBS_PATH . 'pj/css/'; ?>pj.bootstrap.min.css" type="text/css" rel="stylesheet" /&gt;
&lt;link href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoadCss" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFront&action=pjActionLoadJS"&gt;&lt;/script&gt;</div>
		
	<?php
}
?>