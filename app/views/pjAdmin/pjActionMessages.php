<?php
$months = __('months', true);
$days = __('days', true);
?>
if (jQuery_1_8_2.datagrid !== undefined) {
	jQuery_1_8_2.extend(jQuery_1_8_2.datagrid.messages, {
		empty_result: "<?php __('gridEmptyResult', false, true); ?>",
		choose_action: "<?php __('gridChooseAction', false, true); ?>",
		goto_page: "<?php __('gridGotoPage', false, true); ?>",
		total_items: "<?php __('gridTotalItems', false, true); ?>",
		items_per_page: "<?php __('gridItemsPerPage', false, true); ?>",
		prev_page: "<?php __('gridPrevPage', false, true); ?>",
		prev: "<?php __('gridPrev', false, true); ?>",
		next_page: "<?php __('gridNextPage', false, true); ?>",
		next: "<?php __('gridNext', false, true); ?>",
		month_names: ['<?php echo $months[1]; ?>', '<?php echo $months[2]; ?>', '<?php echo $months[3]; ?>', '<?php echo $months[4]; ?>', '<?php echo $months[5]; ?>', '<?php echo $months[6]; ?>', '<?php echo $months[7]; ?>', '<?php echo $months[8]; ?>', '<?php echo $months[9]; ?>', '<?php echo $months[10]; ?>', '<?php echo $months[11]; ?>', '<?php echo $months[12]; ?>'],
		day_names: ['<?php echo $days[1]; ?>', '<?php echo $days[2]; ?>', '<?php echo $days[3]; ?>', '<?php echo $days[4]; ?>', '<?php echo $days[5]; ?>', '<?php echo $days[6]; ?>', '<?php echo $days[0]; ?>'],
		delete_title: "<?php __('gridDeleteConfirmation', false, true); ?>",
		delete_text: "<?php __('gridConfirmationTitle', false, true); ?>",
		action_empty_title: "<?php __('gridEmptyTitle', false, true); ?>",
		action_empty_body: "<?php __('gridEmptyBody', false, true); ?>",
		action_title: "<?php __('gridActionTitle', false, true); ?>",
		btn_ok: "<?php __('gridBtnOk', false, true); ?>",
		btn_cancel: "<?php __('gridBtnCancel', false, true); ?>",
		btn_delete: "<?php __('gridBtnDelete', false, true); ?>"
	});
}

if (jQuery_1_8_2.multilang !== undefined) {
	jQuery_1_8_2.extend(jQuery_1_8_2.multilang.messages, {
		tooltip: "<?php __('multilangTooltip', false, true); ?>"
	});
}

if (cpApp !== undefined) {
	cpApp = jQuery_1_8_2.extend(cpApp, {
		locale: {
			button: <?php echo pjAppController::jsonEncode(__('buttons', true)); ?>,
			validation: <?php echo pjAppController::jsonEncode(__('jquery_validation', true)); ?>
		}
	});
}