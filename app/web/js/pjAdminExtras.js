var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateExtra = $("#frmCreateExtra"),
			$frmUpdateExtra = $("#frmUpdateExtra"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		if ($frmCreateExtra.length > 0 && validate) {
			$frmCreateExtra.validate({
				messages:{
					"price": {
						required: cpApp.locale.validation.required,
						number: cpApp.locale.validation.number
					},
					"cnt": {
						digits: cpApp.locale.validation.digits
					}
				},
				errorPlacement: function (error, element) {
					if(element.attr('name') == 'price' || element.attr('name') == 'cnt')
					{
						error.insertAfter(element.parent().parent());
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
					var localeId = $(validator.errorList[0].element, this).attr('lang');
					if(localeId != undefined)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
				}
			});
			if(myLabel.locale_array.length > 0)
			{
				var locale_array = myLabel.locale_array;
				for(var i = 0; i < locale_array.length; i++)
				{
					var $name = $("#i18n_name_" + locale_array[i]),
						locale = $name.attr('lang');
					$name.rules('add', {
						remote: {
							url: "index.php?controller=pjAdminExtras&action=pjActionCheckExtra",
							type: 'post',
							data: {locale: locale}
						},
						messages: {
					    	required: cpApp.locale.validation.required,
					    	remote: myLabel.same_extra
					    }
					});
				}
			}
			$('#frmCreateExtra .field-int').spinner({
				min: 0
			});
		}
		if ($frmUpdateExtra.length > 0 && validate) {
			$frmUpdateExtra.validate({
				messages:{
					"price": {
						required: cpApp.locale.validation.required,
						number: cpApp.locale.validation.number
					},
					"cnt": {
						digits: cpApp.locale.validation.digits
					}
				},
				errorPlacement: function (error, element) {
					if(element.attr('name') == 'price' || element.attr('name') == 'cnt')
					{
						error.insertAfter(element.parent().parent());
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
					var localeId = $(validator.errorList[0].element, this).attr('lang');
					if(localeId != undefined)
					{
						$(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == localeId)
							{
								$(this).addClass('pj-form-langbar-item-active');
							}else{
								$(this).removeClass('pj-form-langbar-item-active');
							}
						});
					}
				}
			});
			if(myLabel.locale_array.length > 0)
			{
				var locale_array = myLabel.locale_array;
				for(var i = 0; i < locale_array.length; i++)
				{
					var $name = $("#i18n_name_" + locale_array[i]),
						locale = $name.attr('lang'),
						id = $frmUpdateExtra.find("input[name='id']").val();
					$name.rules('add', {
						remote: {
							url: "index.php?controller=pjAdminExtras&action=pjActionCheckExtra",
							type: 'post',
							data: {id: id, locale: locale}
						},
						messages: {
					    	required: cpApp.locale.validation.required,
					    	remote: myLabel.same_extra
					    }
					});
				}
			}
			
			$('#frmUpdateExtra .field-int').spinner({
				min: 0
			});
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminExtras&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtra&id={:id}"}
				          ],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 300},
				          {text: myLabel.price, type: "text", sortable: true, editable: false, width: 200},
				          {text: myLabel.count, type: "text", sortable: true, editable: false, width: 80, align: 'center'}
				         ],
				dataUrl: "index.php?controller=pjAdminExtras&action=pjActionGetExtra",
				dataExtra: "json",
				fields: ['name', 'price', 'cnt'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtraBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminExtras&action=pjActionSaveExtra&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("change", "select[name='type']", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var type = $(this).val();
			if(type == 'unlimited')
			{
				$('.cpLimitedCount').hide();
			}else{
				$('.cpLimitedCount').show();
			}
			return false;
		});
	});
})(jQuery_1_8_2);