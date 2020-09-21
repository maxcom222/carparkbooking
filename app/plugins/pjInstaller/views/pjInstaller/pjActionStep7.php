<?php
include dirname(__FILE__) . '/elements/progress.php';
?>
<div class="i-wrap">
	
	<div class="i-status i-status-success">
		<div class="i-status-icon"><abbr></abbr></div>
		<div class="i-status-txt">
			<h2>Installation successful!</h2>
			<p class="t10">You can login product administration page <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogin">here</a>.</p>
		</div>
	</div>
	
</div>