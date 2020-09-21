var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmUpdatePrice = $("#frmUpdatePrice"),
			$frmUpdateDiscount = $("#frmUpdateDiscount"),
			$frmUpdateCode = $("#frmUpdateCode"),
			$dialogDuplicate = $('#dialogDuplicate'),
			$dialogDuplicateDiscount = $('#dialogDuplicateDiscount'),
			$dialogEmptyPrice = $('#dialogEmptyPrice'),
			dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		if ($dialogEmptyPrice.length > 0 && dialog) 
		{
			$dialogEmptyPrice.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				width: 380,
				buttons: (function () {
					var buttons = {};
					
					buttons[cpApp.locale.button.ok] = function () {
						$dialogEmptyPrice.dialog("close");
						var form =  $dialogEmptyPrice.data('form');
						form.submit();
					};
										
					return buttons;
				})()
			});
		}
		if ($dialogDuplicate.length > 0 && dialog) 
		{
			$dialogDuplicate.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				width: 380,
				buttons: (function () {
					var buttons = {};
					
					buttons[cpApp.locale.button.ok] = function () {
						$dialogDuplicate.dialog("close");
						var $tr = $dialogDuplicate.data('tr'),
							$next_tr = $dialogDuplicate.data('next_tr'),
							seconds = 2000,
							tr_background = $tr.css('backgroundColor'),
							next_tr_background = $next_tr.css('backgroundColor');
						$tr.css('backgroundColor', '#FFB4B4');
						$next_tr.css('backgroundColor', '#FFB4B4');
						setTimeout(function(){
							$tr.css("backgroundColor", tr_background);
							$next_tr.css("backgroundColor", next_tr_background);
						}, seconds);
					};
					
					return buttons;
				})()
			});
		}
		if ($dialogDuplicateDiscount.length > 0 && dialog) 
		{
			$dialogDuplicateDiscount.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				width: 380,
				buttons: (function () {
					var buttons = {};
					
					buttons[cpApp.locale.button.ok] = function () {
						$dialogDuplicateDiscount.dialog("close");
						var $tr = $dialogDuplicateDiscount.data('tr'),
							$next_tr = $dialogDuplicateDiscount.data('next_tr'),
							seconds = 2000,
							tr_background = $tr.css('backgroundColor'),
							next_tr_background = $next_tr.css('backgroundColor');
						$tr.css('backgroundColor', '#FFB4B4');
						$next_tr.css('backgroundColor', '#FFB4B4');
						setTimeout(function(){
							$tr.css("backgroundColor", tr_background);
							$next_tr.css("backgroundColor", next_tr_background);
						}, seconds);
					};
					
					return buttons;
				})()
			});
		}
		
		if ($frmUpdatePrice.length > 0 && validate) {
			$frmUpdatePrice.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				submitHandler: function(form){
					var duplidated = false,
						empty = false;
						$duplicated_tr = null,
						$duplicated_next_tr = null;
					$('#tblPrices > tbody > tr').each(function(index){
						var $tr = $(this),
							id = $tr.attr('data-id'),
							space_id = $('select[name="space_id['+id+']"]').val(),
							date_from = $('input[name="date_from['+id+']"]').val(),
							date_to = $('input[name="date_to['+id+']"]').val();
						
						if($tr.find('.cpNoPrices').length == 0)
						{
							$('#tblPrices > tbody > tr').each(function(idx){
								if(idx > index)
								{
									var $next_tr = $(this),
										next_id = $next_tr.attr('data-id'),
										next_space_id = $('select[name="space_id['+next_id+']"]').val(),
										next_date_from = $('input[name="date_from['+next_id+']"]').val(),
										next_date_to = $('input[name="date_to['+next_id+']"]').val();
								}
								
								if(space_id == next_space_id && date_from == next_date_from && date_to == next_date_to)
								{
									duplidated = true;
									$duplicated_tr = $tr;
									$duplicated_next_tr = $next_tr;
										
									return false;
								}
							});
						}else{
							empty = true;
						}
					});
					if(duplidated == true)
					{
						$dialogDuplicate.data('tr', $duplicated_tr).data('next_tr', $duplicated_next_tr).dialog('open');
					}else{
						if(empty == true)
						{
							$dialogEmptyPrice.data('form', form).dialog('open');
						}else{
							form.submit();
						}
					}
					return false;
				}
			});
		}
		
		if ($frmUpdateDiscount.length > 0 && validate) {
			$frmUpdateDiscount.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				submitHandler: function(form){
					var duplidated = false,
						$duplicated_tr = null,
						$duplicated_next_tr = null;
					$('#tblDiscounts > tbody > tr').each(function(index){
						var $tr = $(this),
							id = $tr.attr('data-id'),
							space_id = $('select[name="space_id['+id+']"]').val(),
							price_id = $('select[name="price_id['+id+']"]').val(),
							min_days = $('input[name="min_days['+id+']"]').val(),
							max_days = $('input[name="max_days['+id+']"]').val();
						$('#tblDiscounts > tbody > tr').each(function(idx){
							if(idx > index)
							{
								var $next_tr = $(this),
									next_id = $next_tr.attr('data-id'),
									next_space_id = $('select[name="space_id['+next_id+']"]').val(),
									next_price_id = $('select[name="price_id['+next_id+']"]').val(),
									next_min_days = $('input[name="min_days['+next_id+']"]').val(),
									next_max_days = $('input[name="max_days['+next_id+']"]').val();
							}
							
							if(space_id == next_space_id && price_id == next_price_id && min_days == next_min_days && max_days == next_max_days)
							{
								duplidated = true;
								$duplicated_tr = $tr;
								$duplicated_next_tr = $next_tr;
									
								return false;
							}
						});
					});
					if(duplidated == true)
					{
						$dialogDuplicateDiscount.data('tr', $duplicated_tr).data('next_tr', $duplicated_next_tr).dialog('open');
					}else{
						form.submit();
					}
					return false;
				}
			});
			
			$('#frmUpdateDiscount .field-int').spinner({
				min: 0
			});
		}
		
		if ($frmUpdateCode.length > 0 && validate) {
			$frmUpdateCode.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: ""
			});
		}
		
		$(document).on("click", ".pj-form-field-icon-date", function (e) {
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
		}).on("click", ".cpAddPrice", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $tbody = $("#tblPrices tbody"),
				index = Math.ceil(Math.random() * 999999);
			
			var clone_text = $("#tblPricesClone").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'cp_' + index);
			if($tbody.find(".cpNoPrices").length == 0)
			{
				$tbody.append(clone_text);
			}else{
				$tbody.html(clone_text);
			}
		}).on("click", ".cpRemovePrice", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tbody = $("#tblPrices tbody"),
				$tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
				if($tbody.find('tr').length == 0)
				{
					$tbody.html('<tr><td colspan="5" class="cpNoPrices">'+myLabel.no_prices_defined+'</td></tr>');
				}
			});	
			return false;
		}).on("click", ".cpAddDiscount", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $tbody = $("#tblDiscounts tbody"),
				index = Math.ceil(Math.random() * 999999);
			
			var clone_text = $("#tblDiscountsClone").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'cp_' + index);
			if($tbody.find(".cpNoDiscounts").length == 0)
			{
				$tbody.append(clone_text);
			}else{
				$tbody.html(clone_text);
			}
			$("#tblDiscounts tbody .field-int").spinner({
				min: 0
			});
		}).on("click", ".cpRemoveDiscount", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tbody = $("#tblDiscounts tbody"),
				$tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
				if($tbody.find('tr').length == 0)
				{
					$tbody.html('<tr><td colspan="6" class="cpNoDiscounts">'+myLabel.no_discounts_defined+'</td></tr>');
				}
			});	
			return false;
		}).on("change", ".cpSpace", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				value = $(this).val(),
				index = $this.attr('data-index');
			$.get("index.php?controller=pjAdminPrices&action=pjActionGetDateRange", {
				"space_id": value,
				"index": index
			}).done(function (data) {
				$('#cpPrice_' + index).html(data);
			});	
			return false;
		}).on("click", ".cpAddCode", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $tbody = $("#tblCodes tbody"),
				index = Math.ceil(Math.random() * 999999);
			
			var clone_text = $("#tblCodesClone").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'cp_' + index);
			if($tbody.find(".cpNoCodes").length == 0)
			{
				$tbody.append(clone_text);
			}else{
				$tbody.html(clone_text);
			}
		}).on("click", ".cpRemoveCode", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tbody = $("#tblCodes tbody"),
				$tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
				if($tbody.find('tr').length == 0)
				{
					$tbody.html('<tr><td colspan="6" class="cpNoCodes">'+myLabel.no_codes_defined+'</td></tr>');
				}
			});	
			return false;
		}).on("change", ".cpType", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				value = $(this).val(),
				index = $this.attr('data-index'),
				$element = $('.cpDiscount_' + index),
				$children = $('.cpDiscount_' + index).children();
			if(value == 'amount')
			{
				$element.removeClass('pj-form-field-custom-after').addClass('pj-form-field-custom-before');
				$children.eq(0).show();
				$children.eq(2).hide();
			}else{
				$element.removeClass('pj-form-field-custom-before').addClass('pj-form-field-custom-after');
				$children.eq(2).show();
				$children.eq(0).hide();
			}
			return false;
		});
	});
})(jQuery_1_8_2);