var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $dialogExecute = $("#dialogExecute"),
			$dialogExecuteAll = $("#dialogExecuteAll"),
			$dialogNotice = $("#dialogNotice"),
			datagrid = ($.fn.datagrid !== undefined),
			dialog = ($.fn.dialog !== undefined);
		
		function formatButton(str, obj) {
			return ['<input type="button" value="', myLabel.execute, '" class="pj-button btn-execute" data-name="', obj.name, 
			        '" data-module="', obj.module,
			        '" data-path="', obj.path, '" />'].join("");
		}
		
		function formatView (str, obj) {
			return ['<a href="index.php?controller=pjInstaller&action=pjActionSecureView&p=', str, '" class="install-view" target="_blank"></a>',
			        (obj.is_new ? ['<input type="hidden" name="record[]" value="', obj.path ,'"><input type="hidden" name="module[]" value="',obj.module,'">'].join("") : '')].join("");
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var columns = [{text: "", type: "text", sortable: false, editable: false, width: 23, renderer: formatView},
					       {text: myLabel.name, type: "text", sortable: false, editable: false, width: 470},
					       {text: myLabel.label, type: "text", sortable: false, editable: false, width: 180},
					       {text: myLabel.dt, type: "text", sortable: false, editable: false, width: 220}];
			var fields = ['base', 'name', 'label', 'date'];
			
			if (window.location.search.match(/&admin=1/)) {
				columns.push({text: "", type: "text", sortable: false, editable: false, width: 100, align: "center", renderer: formatButton});
				fields.push('path');
			}
			
			var $grid = $("#grid").datagrid({
				buttons: [],
				columns: columns,
				dataUrl: "index.php?controller=pjInstaller&action=pjActionSecureGetUpdate",
				dataType: "json",
				fields: fields,
				paginator: false,
				saveUrl: null,
				select: false,
				onRender: function () {
					if ($grid.find('input[name="record[]"]').length > 0) {
						$(".btn-execute-all").show();
					} else {
						$(".btn-execute-all").hide();
					}
				}
			});
		}
		
		$(document).on("click", ".btn-execute-all", function (e) {
			if ($dialogExecuteAll.length > 0 && dialog) {
				$dialogExecuteAll.dialog("open");
			}
		}).on("click", ".btn-execute", function (e) {
			if ($dialogExecute.length > 0 && dialog) {
				var $this = $(this);
				$dialogExecute
					.data("name", $this.data("name"))
					.data("path", $this.data("path"))
					.data("module", $this.data("module"))
					.dialog("open");
			}
		});
		
		if ($dialogExecuteAll.length > 0 && dialog) {
			$dialogExecuteAll.dialog({
				modal: true,
				autoOpen: false,
				draggable: false,
				resizable: false,
				close: function () {
					$dialogExecuteAll.find(".i-error-clean").hide().html("");
				},
				buttons: {
					"Execute": function () {
						$.post("index.php?controller=pjInstaller&action=pjActionSecureSetUpdate", $grid.find('input[name="record[]"], input[name="module[]"]').serialize()).done(function (data) {
							if (data.status == "OK") {
								$dialogExecuteAll.dialog("close");
								
								$dialogNotice.data("content", "Database update has been applied.").dialog("open");
								
								var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjInstaller&action=pjActionSecureGetUpdate", "", "", content.page, content.rowCount);
							
							} else {
								$dialogExecuteAll.find(".i-error-clean").html(data.text).show();
							}
						});
					},
					"Cancel": function () {
						$dialogExecuteAll.dialog("close");
					}
				}
			});
		}
		
		if ($dialogExecute.length > 0 && dialog) {
			$dialogExecute.dialog({
				modal: true,
				autoOpen: false,
				draggable: false,
				resizable: false,
				close: function () {
					$dialogExecute.find(".i-error-clean").hide().html("");
				},
				buttons: {
					"Execute": function () {
						$.post("index.php?controller=pjInstaller&action=pjActionSecureSetUpdate", {
							"name": $dialogExecute.data("name"),
							"path": $dialogExecute.data("path"),
							"module": $dialogExecute.data("module")
						}).done(function (data) {
							if (data.status == "OK") {
								$dialogExecute.dialog("close");
								
								$dialogNotice.data("content", "Database update has been applied.").dialog("open");
								
								var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjInstaller&action=pjActionSecureGetUpdate", "", "", content.page, content.rowCount);
							
							} else {
								$dialogExecute.find(".i-error-clean").html(data.text).show();
							}
						});
					},
					"Cancel": function () {
						$dialogExecute.dialog("close");
					}
				}
			});
		}
		
		if ($dialogNotice.length > 0 && dialog) {
			$dialogNotice.dialog({
				modal: true,
				autoOpen: false,
				draggable: false,
				resizable: false,
				open: function () {
					$dialogNotice.html($dialogNotice.data("content"));
				},
				buttons: {
					"OK": function () {
						$dialogNotice.dialog("close");
					}
				}
			});
		}
		
	});
})(jQuery_1_8_2);