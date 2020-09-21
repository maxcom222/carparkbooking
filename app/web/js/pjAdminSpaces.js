var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateSpace = $("#frmCreateSpace"),
			$frmUpdateSpace = $("#frmUpdateSpace"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		if ($frmCreateSpace.length > 0 && validate) {
			$frmCreateSpace.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
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
							url: "index.php?controller=pjAdminSpaces&action=pjActionCheckSpace",
							type: 'post',
							data: {locale: locale}
						},
						messages: {
					    	required: myLabel.field_required,
					    	remote: myLabel.same_space
					    }
					});
					
					var $desc = $("#i18n_description_" + locale_array[i]),
						locale = $desc.attr('lang');
					$desc.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
				}
			}
			
			$('#frmCreateSpace .field-int').spinner({
				min: 0
			});
		}
		if ($frmUpdateSpace.length > 0 && validate) {
			$frmUpdateSpace.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
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
						id = $frmUpdateSpace.find("input[name='id']").val();
					$name.rules('add', {
						remote: {
							url: "index.php?controller=pjAdminSpaces&action=pjActionCheckSpace",
							type: 'post',
							data: {id: id, locale: locale}
						},
						messages: {
					    	required: myLabel.field_required,
					    	remote: myLabel.same_space
					    }
					});
					
					var $desc = $("#i18n_description_" + locale_array[i]),
						locale = $desc.attr('lang');
					$desc.rules('add', {
						messages: {
					    	required: myLabel.field_required
					    }
					});
				}
			}
			
			$('#frmUpdateSpace .field-int').spinner({
				min: 0
			});
		}
		function formatPrice (str, obj) {
			if(str == '')
			{
				return '<a href="index.php?controller=pjAdminPrices&action=pjActionIndex">'+myLabel.set_price+'</a>';
			}else{
				return str;
			}
		}
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminSpaces&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminSpaces&action=pjActionDeleteSpace&id={:id}"}
				          ],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 180},
				          {text: myLabel.occupied_now, type: "text", sortable: true, editable: false, width: 110, align: 'center'},
				          {text: myLabel.available_now, type: "text", sortable: true, editable: false, width: 110, align: 'center'},
				          {text: myLabel.price_today, type: "text", sortable: true, editable: false, width: 90, renderer: formatPrice},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, width: 100, options: [
				                                                                                     {label: myLabel.active, value: "T"}, 
				                                                                                     {label: myLabel.inactive, value: "F"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminSpaces&action=pjActionGetSpace",
				dataSpace: "json",
				fields: ['name', 'booked', 'avail', 'price', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminSpaces&action=pjActionDeleteSpaceBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminSpaces&action=pjActionSaveSpace&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpaces&action=pjActionGetSpace", "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpaces&action=pjActionGetSpace", "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpaces&action=pjActionGetSpace", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				if(!$dp.is('[disabled=disabled]'))
				{
					$dp.trigger("focusin").datepicker("show");
				}
			}
		}).on("focusin", ".datepick", function (e) {
			var minDate, maxDate,
				$this = $(this),
				index = $(this).data('index'),
				custom = {},
				o = {
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev"),
					onClose: function(dateText){
						$this.valid();
					}
				};
			switch ($this.attr("name")) {
			case "date_from["+index+"]":
				maxDate = $this.closest("tr").find(".datepick[name='date_to["+index+"]']").datepicker({
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev")
				}).datepicker("getDate");
				$this.closest("tr").find(".datepick[name='date_to["+index+"]']").datepicker("destroy").removeAttr("id");
				if (maxDate !== null) {
					custom.maxDate = maxDate;
				}
				break;
			case "date_to["+index+"]":
				minDate = $this.closest("tr").find(".datepick[name='date_from["+index+"]']").datepicker({
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev")
				}).datepicker("getDate");
				$this.closest("tr").find(".datepick[name='date_from["+index+"]']").datepicker("destroy").removeAttr("id");
				if (minDate !== null) {
					custom.minDate = minDate;
				}
				break;
			}
			$this.not('.hasDatepicker').datepicker($.extend(o, custom));
		}).on("click", ".cpAddDate", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tbody = $("#tblSpaces tbody"),
				index = Math.ceil(Math.random() * 999999);
			
			var clone_text = $("#tblSpacesClone").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'cp_' + index);
			$tbody.append(clone_text);
			$("#tblSpaces tbody .field-int").spinner({
				min: 0
			});
			return false;
		}).on("click", ".cpRemoveDate", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
			});	
			return false;
		});
	});
})(jQuery_1_8_2);