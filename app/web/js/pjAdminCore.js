var cpApp = cpApp || {};
var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var button = ($.fn.button !== undefined);
		
		$(".pj-table tbody tr").hover(
			function () {
				$(this).addClass("pj-table-row-hover");
			}, 
			function () {
				$(this).removeClass("pj-table-row-hover");
			}
		);
		$(".pj-button").hover(
			function () {
				$(this).addClass("pj-button-hover");
			}, 
			function () {
				$(this).removeClass("pj-button-hover");
			}
		);
		$(".pj-checkbox").hover(
				function () {
					$(this).addClass("pj-checkbox-hover");
				}, 
				function () {
					$(this).removeClass("pj-checkbox-hover");
				}
			);
		$("#content").on("click", ".notice-close", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).closest(".notice-box").fadeOut();
			return false;
		});
		
		if ($.noty !== undefined) {
			$.noty.defaults = $.extend($.noty.defaults, {
				layout: "bottomRight",
				timeout: 3000
			});
		}
		
		cpApp.enableButtons = function ($dialog) {
			if ($dialog.length > 0 && button) {
				$dialog.siblings(".ui-dialog-buttonpane").find("button").button("enable");
			}
		};
		
		cpApp.disableButtons = function ($dialog) {
			if ($dialog.length > 0 && button) {
				$dialog.siblings(".ui-dialog-buttonpane").find("button").button("disable");
			}
		};
	});
})(jQuery_1_8_2);