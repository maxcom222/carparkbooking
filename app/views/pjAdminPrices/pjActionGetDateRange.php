<select name="price_id[<?php echo $_GET['index'];?>]" class="pj-form-field w200 required">
	<option value="">-- <?php __('lblChoose'); ?>--</option>
	<?php 
	if(!empty($tpl['arr']))
	{
		foreach ($tpl['arr'] as $price)
		{
			?><option value="<?php echo $price['id']; ?>"><?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_from'])); ?> - <?php echo date($tpl['option_arr']['o_date_format'], strtotime($price['date_to'])); ?></option><?php
		}
	}
	?>
</select>