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
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/menu_spaces.php';
	pjUtil::printNotice(__('infoSpacesTitle', true), __('infoSpacesDesc', true));
	?>
	<div class="b10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
			<input type="hidden" name="controller" value="pjAdminSpaces" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<input type="submit" class="pj-button" value="<?php __('btnAddSpace'); ?>" />
		</form>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
		<br class="clear_both" />
	</div>
	
	<div id="grid"></div>
	<?php
	$filter = __('filter', true); 
	?>
	<script type="text/javascript">
		var myLabel = myLabel || {};
		myLabel.name = "<?php __('lblName'); ?>";
		myLabel.occupied_now = "<?php __('lblOccupiedNow');?>";
		myLabel.available_now = "<?php __('lblAvailableNow');?>";
		myLabel.price_today = "<?php __('lblPriceToday');?>";
		myLabel.set_price = "<?php __('lblSetPrice');?>";
		myLabel.total_spaces_today = "<?php __('lblTotalSpacesToday'); ?>";
		myLabel.avail_spaces_today = "<?php __('lblAvailableSpacesToday'); ?>";
		myLabel.status = "<?php __('lblStatus'); ?>";
		myLabel.active = "<?php echo $filter['active']; ?>";
		myLabel.inactive = "<?php echo $filter['inactive']; ?>";
		myLabel.delete_selected = "<?php __('delete_selected'); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation'); ?>";
	</script>
	<?php
}
?>