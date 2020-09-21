(function ($, undefined) {
	$(function () {
		var datagrid = ($.fn.datagrid !== undefined);
		
		if ($("#grid").length > 0 && datagrid) {
			
			var gridOpts = {
				buttons: [],
				columns: [{text: myLabel.created, type: "text", sortable: true, editable: false, width: 120},
				          {text: myLabel.number, type: "text", sortable: true, editable: false, width: 130},
				          {text: myLabel.text, type: "text", sortable: true, editable: false},
				          {text: myLabel.status, type: "text", sortable: true, editable: false, width: 140}
				          ],
				dataUrl: "index.php?controller=pjSms&action=pjActionGetSms" + pjGrid.queryString,
				dataType: "json",
				fields: ['created', 'number', 'text', 'status'],
				paginator: {
					actions: [],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				}
			};
			
			var $grid = $("#grid").datagrid(gridOpts);
			
			$(document).on("submit", ".frm-filter", function (e) {
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
				$grid.datagrid("load", "index.php?controller=pjSms&action=pjActionGetSms", "id", "ASC", content.page, content.rowCount);
				return false;
			});
		}
		
	});
})(jQuery);