<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice('Login required', 'In order to access this page you need to log in first.', false, false);
			break;
	}
} else {
	?>
	<div class="i-wrap">
		
		<?php
		pjUtil::printNotice('Plugin Install', 'Use this tool to install plugins added after initial script installation.', true, false);
		?>
		
		<div id="grid"></div>
			
		<div id="dialogInstall" style="display: none" title="Install confirmation">Are you sure you want to install selected plugin?
			<label class="i-error-clean" style="display: none"></label>
		</div>
		
		<div id="dialogNotice" style="display: none" title="System notice"></div>

		<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.name = 'Plugin';
		myLabel.dt = 'Installed on';
		myLabel.install = 'Install';
		myLabel.uninstall = 'Uninstall';
		</script>
	
	</div>
	<?php
}
?>