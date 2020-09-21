var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var datagrid = ($.fn.datagrid !== undefined);

		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "delete", url: "index.php?controller=pjOneAdmin&action=pjActionDelete&id={:id}"}],
				columns: [{text: "Script name", type: "text", sortable: true, editable: true, width: 200, editableWidth: 185},
				          {text: "URL", type: "text", sortable: true, editable: true, width: 250, editableWidth: 235},
				          {text: "Email", type: "text", sortable: true, editable: true, width: 200, editableWidth: 185},
				          {text: "Password", type: "text", sortable: true, editable: true, width: 200, editableWidth: 185}],
				dataUrl: "index.php?controller=pjOneAdmin&action=pjActionGet",
				dataType: "json",
				fields: ['name', 'url', 'email', 'password'],
				paginator: {
					actions: [
					   {text: "Delete selected", url: "index.php?controller=pjOneAdmin&action=pjActionDeleteBulk", render: true, confirmation: "Are you sure you want to delete selected records?"}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjOneAdmin&action=pjActionSave&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
			
			$(document).on("click", ".btn-add", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				$.post("index.php?controller=pjOneAdmin&action=pjActionSave").done(function (data) {
					$grid.datagrid("option", "onRender", function () {
						$("tr[data-id='id_" + data.id + "']").find(".pj-table-cell-editable").filter(":first").trigger("click");
						$grid.datagrid("option", "onRender", null);
					});
					$grid.datagrid("load", "index.php?controller=pjOneAdmin&action=pjActionGet");
				});
				return false;
			});
		}
	});
})(jQuery_1_8_2);