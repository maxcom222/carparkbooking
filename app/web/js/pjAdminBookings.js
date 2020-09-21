var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateBooking = $("#frmCreateBooking"),
			$frmUpdateBooking = $("#frmUpdateBooking"),
			$dialogConfirm = $("#dialogConfirm"),
			validate = ($.fn.validate !== undefined),
			chosen = ($.fn.chosen !== undefined);
			dialog = ($.fn.dialog !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			tabs = ($.fn.tabs !== undefined),
			$tabs = $("#tabs"),
			tOpt = {
				select: function (event, ui) {
					$(":input[name='tab_id']").val(ui.panel.id);
				}
			};
			
		if ($tabs.length > 0 && tabs) 
		{
			$tabs.tabs(tOpt);
		}
		if (chosen) 
		{
			$("#c_country").chosen();
		}
		if ($frmCreateBooking.length > 0 && validate) {
			$frmCreateBooking.validate({
				rules: {
					"uuid": {
						required: true,
						remote: "index.php?controller=pjAdminBookings&action=pjActionCheckUniqueId"
					}
				},
				errorPlacement: function (error, element) {
					if(element.hasClass('cpExtraPrice') || element.hasClass('cpExtraSelector'))
					{
						error.insertAfter(element);
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();
				    	if ($tabs.length > 0 && tabs && index !== -1) {
				    		$tabs.tabs(tOpt).tabs("option", "active", index-1);
				    	}
				    };
				}
			});
			
		}
		if ($frmUpdateBooking.length > 0 && validate) {
			$frmUpdateBooking.validate({
				rules: {
					"uuid": {
						required: true,
						remote: "index.php?controller=pjAdminBookings&action=pjActionCheckUniqueId&id=" + $frmUpdateBooking.find("input[name='id']").val()
					}
				},
				messages: {
					"uuid":{
						remote: myLabel.existing_id
					}
				},
				errorPlacement: function (error, element) {
					if(element.hasClass('cpExtraPrice'))
					{
						error.insertAfter(element);
					}else{
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();
				    	if ($tabs.length > 0 && tabs && index !== -1) {
				    		$tabs.tabs(tOpt).tabs("option", "active", index-1);
				    	}
				    };
				}
			});
			$frmUpdateBooking.find(".field-int").spinner({
				min: 0
			});
			
			tinymce.init({
				relative_urls : false,
				remove_script_host : false,
			    selector: "textarea.mceEditor",
			    theme: "modern",
			    width: 600,
			    height: 250,
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
			   ],
			   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"
			 });
		}
		
		if ($dialogConfirm.length > 0 && dialog) {
			$dialogConfirm.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 650,
				open: function () {
					validator = $dialogConfirm.find("form").validate({
						errorPlacement: function (error, element) {
							error.insertAfter(element);
						},
						errorClass: "error_clean"
					});
				},
				buttons: (function () {
					var buttons = {};
					buttons[cpApp.locale.button.send] = function () {
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionSend", $dialogConfirm.find("form").serialize()).done(function (data) {
								if (data.status == "OK") {
									noty({text: data.text, type: "success"});
								} else {
									noty({text: data.text, type: "error"});
								}
								$dialogConfirm.dialog("close");
							});
						}
					};
					buttons[cpApp.locale.button.cancel] = function () {
						$dialogConfirm.dialog("close");
					};
					
					return buttons;
				})()
			});
		}
		
		function _formatName(val, obj) {
			return ['<a href="index.php?controller=pjAdminBookings&action=pjActionUpdate&id=', obj.id, '">', val, '</a>'].join("");
		}
		if ($("#grid").length > 0 && datagrid) {
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBooking&id={:id}"},
						 ],
				columns: [
		          		  {text: myLabel.date_time, type: "text", sortable: true, editable: false, width: 240},
		          		  {text: myLabel.space, type: "text", sortable: true, editable: false, width: 130},
				          {text: myLabel.name, type: "text", sortable: true, editable: false, width: 130, renderer: _formatName},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, width: 100, options: [
				                                                                                     {label: myLabel.pending, value: "pending"}, 
				                                                                                     {label: myLabel.confirmed, value: "confirmed"},
				                                                                                     {label: myLabel.cancelled, value: "cancelled"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString,
				dataType: "json",
				fields: ['from', 'space', 'c_name', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings&action=pjActionSaveBooking&id={:id}",
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
				q: "",
				uuid: "",
				c_name: "",
				c_email: "",
				space_id: "",
				status: "",
				date_from: "",
				date_to: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, "created", "DESC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val(),
				uuid: "",
				c_name: "",
				c_email: "",
				space_id: "",
				status: "",
				date_from: "",
				date_to: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-button-detailed, .pj-button-detailed-arrow", function (e) {
			e.stopPropagation();
			$(".pj-form-filter-advanced").toggle();
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = {},
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, "created", "DESC", content.page, content.rowCount);
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			var $frm = $('.frm-filter-advanced');
			$(".pj-button-detailed").trigger("click");
			
			$frm.find("input[name='uuid']").val('');
			$frm.find("input[name='c_name']").val('');
			$frm.find("input[name='c_email']").val('');
			$frm.find("input[name='space_id']").val('');
			$frm.find("input[name='status']").val('');
			$frm.find("input[name='date_from']").val('');
			$frm.find("input[name='date_to']").val('');
			
		}).on("change", "#payment_method", function (e) {
			switch ($("option:selected", this).val()) {
				case 'creditcard':
					$(".boxCC").show();
					break;
				default:
					$(".boxCC").hide();
			}
		}).on("focusin", ".datetimepick", function (e) {
			var minDateTime, maxDateTimes,
				$this = $(this),
				custom = {},
				o = {
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev"),
					timeFormat: $this.attr("lang"),
					stepMinute: 5,
					minDate: 0,
					onClose: function (dateTimeText){
						var currentDateTime = $this.attr('data-date');
						if((currentDateTime != dateTimeText && currentDateTime != '') || (currentDateTime == '' && $(".datetimepick[name='from']").val() != '' && $(".datetimepick[name='to']").val() != ''))
						{
							$this.attr('data-date', dateTimeText);
							var $frm = $this.closest("form");
							$.post("index.php?controller=pjAdminBookings&action=pjActionGetSpaces", $frm.serialize()).done(function(data) {
								$('#cpSpaceContainer').html(data.ob_spaces);
								$('#cpRentalDays').html(data.ob_days);
								$('#tblExtrasClone').html(data.ob_extras);
								resetCalc.call(null);
							});
						}
					}
			};
			switch ($this.attr("name")) {
			case "from":
				if($(".datetimepick[name='to']").val() != '')
				{
					maxDateTime = $(".datetimepick[name='to']").datetimepicker({
						firstDay: $this.attr("rel"),
						dateFormat: $this.attr("rev"),
						timeFormat: $this.attr("lang")
					}).datetimepicker("getDate");
					$(".datetimepick[name='to']").datepicker("destroy").removeAttr("id");
					if (maxDateTime !== null) {
						custom.maxDateTime = maxDateTime;
					}
				}
				break;
			case "to":
				if($(".datetimepick[name='from']").val() != '')
				{
					minDateTime = $(".datetimepick[name='from']").datetimepicker({
						firstDay: $this.attr("rel"),
						dateFormat: $this.attr("rev"),
						timeFormat: $this.attr("lang")
					}).datetimepicker("getDate");
					$(".datetimepick[name='from']").datepicker("destroy").removeAttr("id");
					if (minDateTime !== null) {
						custom.minDateTime = minDateTime;
					}
				}
				break;
			}
			$(this).datetimepicker($.extend(o, custom));
			
		}).on("focusin", ".datepick", function (e) {
			var minDate, maxDate,
				$this = $(this),
				custom = {},
				o = {
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev")
				};
			switch ($this.attr("name")) {
				case "date_from":
					if($(".datepick[name='date_to']").val() != '')
					{
						maxDate = $(".datepick[name='date_to']").datepicker({
							firstDay: $this.attr("rel"),
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='date_to']").datepicker("destroy").removeAttr("id");
						if (maxDate !== null) {
							custom.maxDate = maxDate;
						}
					}
					break;
				case "date_to":
					if($(".datepick[name='date_from']").val() != '')
					{
						minDate = $(".datepick[name='date_from']").datepicker({
							firstDay: $this.attr("rel"),
							dateFormat: $this.attr("rev")
						}).datepicker("getDate");
						$(".datepick[name='date_from']").datepicker("destroy").removeAttr("id");
						if (minDate !== null) {
							custom.minDate = minDate;
						}
					}
					break;
			}
			$(this).datepicker($.extend(o, custom));
			
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
			
		}).on("click", ".cpAddExtra", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tbody = $("#tblExtras tbody"),
				index = Math.ceil(Math.random() * 999999);
			
			var clone_text = $("#tblExtrasClone").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'cp_' + index);
			$tbody.append(clone_text);
			return false;
		}).on("click", ".cpRemoveExtra", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
				calcPrice.call(null);
			});	
			return false;
		}).on("change", ".cpExtraSelector", function (e) {
			var $tr = $(this).parent().parent().parent(),
				$td_price = $tr.find("td").eq(1),
				$td_qty = $tr.find("td").eq(2),
				index = $(this).attr('data-index');
			if($(this).val() != '')
			{
				var type = $('option:selected', this).attr('data-type'),
					cnt = $('option:selected', this).attr('data-cnt'),
					is_single = $('option:selected', this).attr('data-is_single'),
					price_format = $('option:selected', this).attr('data-price_format'),
					td_qty_html = '';
				
				$td_qty.html("");
				switch(is_single)
				{
					case "1":
						td_qty_html = '<input type="checkbox" name="qty['+index+']" data-index="'+index+'" class="required cpExtraPrice"/>';
						$td_qty.append(td_qty_html);
						break;
					case "0":
						if(type == 'limited')
						{
							td_qty_html = '<select name="qty['+index+']" data-index="'+index+'" class="pj-form-field w60 required cpExtraPrice">';
							td_qty_html += '<option value="">--</option>';
							for(i = 0; i <= parseInt(cnt, 10); i++)
							{
								td_qty_html += '<option value="'+i+'">'+i+'</option>';
							}
							td_qty_html += '</select>';
							$td_qty.append(td_qty_html);
						}else{
							td_qty_html = '<input type="text" name="qty['+index+']" data-index="'+index+'" class="pj-form-field w60 required cpExtraPrice field-int"/>';
							$td_qty.append(td_qty_html);
							$td_qty.find('.field-int').spinner({
								min: 0,
								stop: function(event,ui){
									calcPrice.call(null);
								}
							});
						}
						break;
				}
				$td_price.html(price_format);
			}else{
				$td_price.html("");
				$td_qty.html("");
			}
			calcPrice.call(null);
			return false;
		}).on("change", "#space_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
				$option = $("option:selected", this);
			if($option.val() == '')
			{
				resetCalc.call(null);
			}else{
				calcPrice.call(null);
			}
		}).on("change", ".cpExtraPrice", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			calcPrice.call(null);
		}).on("click", ".cpSendConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$dialogConfirm.dialog('open');
		}).on("change", "#export_period", function (e) {
			var period = $(this).val();
			if(period == 'last')
			{
				$('#last_label').show();
				$('#next_label').hide();
			}else{
				$('#last_label').hide();
				$('#next_label').show();
			}
		}).on("click", "#file", function (e) {
			$('#cpSubmitButton').val(myLabel.btn_export);
			$('.cpFeedContainer').hide();
			$('.cpPassowrdContainer').hide();
		}).on("click", "#feed", function (e) {
			$('.cpPassowrdContainer').show();
			$('#cpSubmitButton').val(myLabel.btn_get_url);
		}).on("focus", "#bookings_feed", function (e) {
			$(this).select();
		}).on("click", ".cpApplyCode", function (e) {
			if($('#discount_code').val() !=  '')
			{
				var $frm = $(this).closest("form");
				$.post("index.php?controller=pjAdminBookings&action=pjActionApplyCode", $frm.serialize()).done(function(data) {
					if(data.code == 200)
					{
						$('#discount_code').attr('data-type', data.arr.type);
						$('#discount_code').attr('data-discount', data.arr.discount);
						$('.cpBtnCode').hide();
						$('.cpRemoveCode').show();
					}else{
						$('#discount_code').attr('data-type', '');
						$('#discount_code').attr('data-discount', '');
					}
					calcPrice.call(null);
				});
			}
		}).on("click", ".cpRemoveCode", function (e) {
			$('#discount_code').val("");
			$('#discount_code').attr('data-type', '');
			$('#discount_code').attr('data-discount', '');
			$('.cpBtnCode').hide();
			$('.cpApplyCode').show();
			calcPrice.call(null);
		});
		
		function calcPrice()
		{
			var $space_id = $('#space_id'),
				$frm = $space_id.closest('form'),
				rental_price = parseFloat($("option:selected", $space_id).attr('data-price')),
				rental_days = parseInt($("option:selected", $space_id).attr('data-days'), 10),
				total_extra_price = 0,
				discount = 0,
				subtotal = 0,
				tax = 0,
				total = 0,
				deposit = 0;
			if($space_id.val() != '')
			{
				$frm.find('.cpExtraPrice').each(function(){
					var cnt = 0,
						extra_price = 0,
						days = rental_days,
						index = $(this).attr('data-index'),
						$extra_id = $(this).closest('tr').find('.cpExtraSelector'),
						$option = $("option:selected", $extra_id),
						per = $option.attr('data-per'),
						unit_price = parseFloat($option.attr('data-price'));
					if($(this).is(':checkbox'))
					{
						if($(this).is(':checked'))
						{
							cnt = 1;
							if(per == 'booking')
							{
								days = 1;
							}
						}
					}else{
						if($(this).val() != '')
						{
							cnt = parseInt($(this).val(), 10);
							if(cnt > 0)
							{
								if(per == 'booking')
								{
									days = 1;
								}
							}
						}
					}
					extra_price = unit_price * days * cnt;
					$extra_id.next().val(extra_price);
					total_extra_price += extra_price;
				});
				subtotal = (rental_price + total_extra_price);
				
				var type = $('#discount_code').attr('data-type');
				if(type != '')
				{
					discount = parseFloat($('#discount_code').attr('data-discount'));
					if(type == 'percent')
					{
						discount = (subtotal * discount) / 100;
					}
				}
				subtotal = subtotal - discount;
				tax = (subtotal * myLabel.tax_percent) / 100;
				total = subtotal + tax;
				deposit = (subtotal * myLabel.deposit_percent) / 100;
				
				$('#rental_price').val(rental_price.toFixed(2));
				$('#extra_price').val(total_extra_price.toFixed(2));
				$('#discount').val(discount.toFixed(2));
				$('#sub_total').val(subtotal.toFixed(2));
				$('#tax').val(tax.toFixed(2));
				$('#total').val(total.toFixed(2));
				$('#deposit').val(deposit.toFixed(2));
			}else{
				resetCalc.call(null, [1]);
			}
		}
		
		function resetCalc(clear)
		{
			if(clear == null)
			{
				$("#tblExtras tbody").html("");
			}
			$('#rental_price').val("");
			$('#extra_price').val("");
			$('#discount_code').val("");
			$('#discount').val("");
			$('#sub_total').val("");
			$('#tax').val("");
			$('#total').val("");
			$('#deposit').val("");
			$('.cpBtnCode').hide();
			$('.cpApplyCode').show();
		}
	});
})(jQuery_1_8_2);