var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var datagrid = ($.fn.datagrid !== undefined);
		
		if ($("#grid").length > 0 && datagrid) {
			
			function formatFile(val, obj) {
				return ['<a href="index.php?controller=pjBackup&action=pjActionDownload&id=', obj.id, '">', val, '</a>'].join("");
			}
			
			var gridOpts = {
				buttons: [{type: "delete", url: "index.php?controller=pjBackup&action=pjActionDelete&id={:id}"}],
				columns: [{text: myLabel.datetime, type: "text", sortable: true, editable: false, width: 150},
				          {text: myLabel.type, type: "text", sortable: true, editable: false},
						  {text: myLabel.size, type: "text", sortable: true, editable: false},
				          {text: myLabel.file, type: "text", sortable: true, editable: false, renderer: formatFile}
				          ],
				dataUrl: "index.php?controller=pjBackup&action=pjActionGet",
				dataType: "json",
				fields: ['created', 'type', 'size', 'id'],
				paginator: {
					actions: [
						{text: myLabel.delete_selected, url: "index.php?controller=pjBackup&action=pjActionDeleteBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: null,
				select: {
					field: "id",
					name: "record[]"
				}
			};
			
			var $grid = $("#grid").datagrid(gridOpts);
		}
		
	});
})(jQuery_1_8_2);