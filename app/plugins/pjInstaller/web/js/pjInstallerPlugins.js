var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $dialogInstall = $("#dialogInstall"),
			$dialogNotice = $("#dialogNotice"),
			datagrid = ($.fn.datagrid !== undefined),
			dialog = ($.fn.dialog !== undefined);
		
		function formatInstall(str, obj) {
			
			if (obj.is_new === 0) {
				return ['<input type="button" value="', myLabel.install, '" class="pj-button btn-install pj-button-disabled" data-name="', obj.name, '" disabled="disabled" />'].join("");
			}
				
			return ['<input type="button" value="', myLabel.install, '" class="pj-button btn-install" data-name="', obj.name, '" />'].join("");
		}
		
		if ($("#grid").length && datagrid) {
			
			var columns = [{text: myLabel.name, type: "text", sortable: false, editable: false, width: 470},
					       {text: myLabel.dt, type: "text", sortable: false, editable: false, width: 220},
					       {text: "", type: "text", sortable: false, editable: false, width: 100, align: "center", renderer: formatInstall}];
			var fields = ['name', 'date', 'name'];
			
			var $grid = $("#grid").on("click", ".btn-install", function (e) {
				if ($dialogInstall.length && dialog) {
					var $this = $(this);
					$dialogInstall
						.data("name", $this.data("name"))
						.dialog("open");
				}
			}).datagrid({
				buttons: [],
				columns: columns,
				dataUrl: "index.php?controller=pjInstaller&action=pjActionSecureGetPlugins",
				dataType: "json",
				fields: fields,
				paginator: false,
				saveUrl: null,
				select: false
			});
		}

		if ($dialogInstall.length && dialog) {
			$dialogInstall.dialog({
				modal: true,
				autoOpen: false,
				draggable: false,
				resizable: false,
				close: function () {
					$dialogInstall.find(".i-error-clean").hide().html("");
				},
				buttons: {
					"Yes, Install": function () {
						$.post("index.php?controller=pjInstaller&action=pjActionSecureInstallPlugin", {
							"name": $dialogInstall.data("name")
						}).done(function (data) {
							if (data.status && data.status === "OK") {
								$dialogInstall.dialog("close");
								
								$dialogNotice.data("content", "Plugin has been installed.").dialog("open");
								
								var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjInstaller&action=pjActionSecureGetPlugins", "", "", content.page, content.rowCount);
							
							} else {
								$dialogInstall.find(".i-error-clean").html(data.text).show();
							}
						});
					},
					"Cancel": function () {
						$dialogInstall.dialog("close");
					}
				}
			});
		}
		
		if ($dialogNotice.length && dialog) {
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