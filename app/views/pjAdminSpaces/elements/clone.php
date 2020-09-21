<table id="tblSpacesClone" style="display: none">
	<tbody>
		<tr>
			<td>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="date_from[{INDEX}]" class="pj-form-field pointer w90 datepick required" value="<?php echo $today;?>" readonly="readonly" data-index="{INDEX}" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
				</span>
			</td>
			<td>
				<span class="pj-form-field-custom pj-form-field-custom-after">
					<input type="text" name="date_to[{INDEX}]" class="pj-form-field pointer w90 datepick required" value="<?php echo $next_year;?>" readonly="readonly" data-index="{INDEX}" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
				</span>
			</td>
			<td>
				<input type="text" name="spaces[{INDEX}]" class="pj-form-field w70 field-int required digits"/>
			</td>
			<td class="align_center"><a href="#" class="pj-delete cpRemoveDate"></a></td>
		</tr>
	</tbody>
</table>