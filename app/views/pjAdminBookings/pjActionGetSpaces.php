<?php
ob_start();
?>
<select id="space_id" name="space_id" class="pj-form-field w250 required">
	<option value="">-- <?php __('lblChoose'); ?>--</option>
	<?php
	foreach($tpl['space_arr'] as $space)
	{
		if(($space['is_available'] == 1 && (float) $space['price'] > 0))
		{
			?><option value="<?php echo $space['id'];?>" data-days="<?php echo $tpl['rental_days'];?>" data-price="<?php echo $space['price'];?>"><?php echo pjSanitize::html($space['name']);?></option><?php
		}
	} 
	?>
</select>
<?php
$ob_spaces = ob_get_contents();
ob_end_clean();
ob_start();
?>
<label class="content"><?php echo $tpl['rental_days'];?> <?php echo $tpl['rental_days'] == 1 ? __('lblSingularDay') : __('lblPluralDays');?></label>
<?php
$ob_days = ob_get_contents();
ob_end_clean();
ob_start();
?>
<tbody>
	<tr>
		<td>
			<span class="inline-block">
				<select name="extra_id[{INDEX}]" data-index="{INDEX}" class="pj-form-field w200 required cpExtraSelector">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach ($tpl['extra_arr'] as $k => $v)
					{
						$cnt = $v['cnt'] - $v['bookings'];
						if($cnt > 0 || $v['type'] == 'unlimited')
						{	
							?><option value="<?php echo $v['id']; ?>" data-per="<?php echo $v['per'];?>" data-price="<?php echo $v['price'];?>" data-type="<?php echo $v['type'];?>" data-cnt="<?php echo $cnt; ?>" data-is_single="<?php echo $v['is_single'];?>" data-price_format="<?php echo pjSanitize::html($v['price_format']);?>"><?php echo pjSanitize::html($v['name']); ?></option><?php
						}
					}
					?>
				</select>
				<input type="hidden" name="price[{INDEX}]"/>
			</span>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="align_center"><a href="#" class="pj-delete cpRemoveExtra"></a></td>
	</tr>
</tbody>
<?php
$ob_extras = ob_get_contents();
ob_end_clean();
pjAppController::jsonResponse(compact('ob_spaces', 'ob_days', 'ob_extras'));
?>