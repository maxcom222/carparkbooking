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
		pjUtil::printNotice('Database Update', 'You can update either to specified database version or more than just one.', true, false);
		?>
		
		<div id="grid"></div>
		
		<button type="button" class="pj-button btn-execute-all t10" style="display: none">Execute</button>
		
		<div id="dialogExecuteAll" style="display: none" title="Execute confirmation">Are you sure you want to execute file(s) that are not executed yet?
			<label class="i-error-clean" style="display: none"></label>
		</div>

		<div id="dialogExecute" style="display: none" title="Execute confirmation">Are you sure you want to execute selected file?
			<label class="i-error-clean" style="display: none"></label>
		</div>

		<div id="dialogNotice" style="display: none" title="System notice"></div>

		<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.name = 'File name';
		myLabel.label = 'Refers to';
		myLabel.dt = 'Executed on';
		myLabel.execute = 'Execute';
		myLabel.execute_selected = 'Execute Selected';
		myLabel.confirm_selected = 'Are you sure you want to execute selected file(s)?';
		</script>
	
	</div>
	<?php
}
?>