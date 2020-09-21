<?php
if (count($tpl['arr']) > 0)
{
	?>
	<div class="leftmenu-top"></div>
	<div class="leftmenu-middle">
		<div class="menu">
			<form action="" method="post" style="display: inline">
				<input type="hidden" name="login_email" value="" />
				<input type="hidden" name="login_password" value="" />
				<input type="hidden" name="login_user" value="1" />
				<select class="pj-form-field" id="pjOneAdminSelect" style="width: 199px">
					<option value="">---</option>
					<?php
					foreach ($tpl['arr'] as $item)
					{
						?><option value="<?php echo $item['url']; ?>" data-email="<?php echo $item['email']; ?>" data-password="<?php echo $item['password']; ?>"><?php echo $item['name']; ?></option><?php
					}
					?>
				</select>
			</form>
		</div>
	</div>
	<div class="leftmenu-bottom"></div>
	
	<script type="text/javascript">
	(function () {
		var url, opt,
			oa = window.document.getElementById("pjOneAdminSelect");
		if (oa) {
			oa.onchange = function (e) {
				opt = this.options[this.selectedIndex];
				url = opt.value;
				if (url.length > 0) {
					this.form.login_email.value = opt.getAttribute("data-email");
					this.form.login_password.value = opt.getAttribute("data-password");
					this.form.action = url;
					this.form.submit();
				}
			};
		}
	})(window);
	</script>
	<?php
}
?>