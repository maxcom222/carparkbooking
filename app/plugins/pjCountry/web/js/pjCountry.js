var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateCountry = $("#frmCreateCountry"),
			$frmUpdateCountry = $("#frmUpdateCountry"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		if ($frmCreateCountry.length > 0 && validate) {
			$frmCreateCountry.validate({
				rules: {
					alpha_2: {
						rangelength: [2,2],
						remote: "index.php?controller=pjCountry&action=pjActionCheckAlpha"
					},
					alpha_3: {
						rangelength: [3,3],
						remote: "index.php?controller=pjCountry&action=pjActionCheckAlpha"
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if ($frmUpdateCountry.length > 0 && validate) {
			$frmUpdateCountry.validate({
				rules: {
					alpha_2: {
						rangelength: [2,2],
						remote: "index.php?controller=pjCountry&action=pjActionCheckAlpha&id=" + $frmUpdateCountry.find("input[name='id']").val()
					},
					alpha_3: {
						rangelength: [3,3],
						remote: "index.php?controller=pjCountry&action=pjActionCheckAlpha&id=" + $frmUpdateCountry.find("input[name='id']").val()
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjCountry&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjCountry&action=pjActionDeleteCountry&id={:id}"}
				          ],
				columns: [{text: myLabel.country, type: "text", sortable: true, editable: true, width: 390, editableWidth: 280},
				          {text: myLabel.alpha_2, type: "text", sortable: true, editable: true, width: 60, editableWidth: 40},
				          {text: myLabel.alpha_3, type: "text", sortable: true, editable: true, width: 60, editableWidth: 40},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [
				                                                                                     {label: myLabel.active, value: "T"}, 
				                                                                                     {label: myLabel.inactive, value: "F"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjCountry&action=pjActionGetCountry",
				dataType: "json",
				fields: ['name', 'alpha_2', 'alpha_3', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjCountry&action=pjActionDeleteCountryBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.revert_status, url: "index.php?controller=pjCountry&action=pjActionStatusCountry", render: true}					   
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjCountry&action=pjActionSaveCountry&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjCountry&action=pjActionGetCountry", "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjCountry&action=pjActionGetCountry", "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjCountry&action=pjActionGetCountry", "name", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);