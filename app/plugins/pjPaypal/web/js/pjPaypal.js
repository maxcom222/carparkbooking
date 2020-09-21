var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $dialogPaypalInfo = $("#dialogPaypalInfo"),
			dialog = ($.fn.dialog !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		function formatBtn(str, obj) {
			return ['<input type="button" class="pj-button paypal-details" data-id="', obj.id, '" value="', myLabel.btn_view, '" />'].join("");
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [],
				columns: [
				    {text: myLabel.subscr_id, type: "text", sortable: true, editable: false},
				    {text: myLabel.txn_id, type: "text", sortable: true, editable: false},
				    {text: myLabel.txn_type, type: "text", sortable: true, editable: false},
				    {text: myLabel.email, type: "text", sortable: true, editable: false},
				    {text: myLabel.dt, type: "text", sortable: true, editable: false},
				    {text: "", type: "text", sortable: false, editable: false, renderer: formatBtn}
				],
				dataUrl: "index.php?controller=pjPaypal&action=pjActionGetPaypal",
				dataType: "json",
				fields: ['subscr_id', 'txn_id', 'txn_type', 'payer_email', 'dt', 'id'],
				paginator: {
					actions: [],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				select: false
			});
		}
		
		$(document).on("click", ".paypal-details", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogPaypalInfo.length > 0 && dialog) {
				$dialogPaypalInfo.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		});
		
		if ($dialogPaypalInfo.length > 0 && dialog) {
			$dialogPaypalInfo.dialog({
				modal: true,
				autoOpen: false,
				draggable: false,
				resizable: false,
				width: 560,
				open: function () {
					$dialogPaypalInfo.html("");
					$.get("index.php?controller=pjPaypal&action=pjActionGetDetails", {
						"id": $dialogPaypalInfo.data("id")
					}).done(function (data) {
						$dialogPaypalInfo.html(data);
						$dialogPaypalInfo.dialog("option", "position", "center");
					});
				},
				buttons: (function () {
					var btns = {};
					btns[myLabel.btn_close] = function () {
						$dialogPaypalInfo.dialog("close");
					};
					return btns;
				})()
			});
		}
	});
})(jQuery_1_8_2);