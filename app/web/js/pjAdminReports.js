var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		
		$(document).on("focusin", ".datepick", function (e) {
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
						custom.minDate= minDate;
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
			
		});
	});
})(jQuery_1_8_2);