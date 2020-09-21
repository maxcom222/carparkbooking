(function ($, undefined) {
	$(function () {
		var $frmStep1 = $('#frmStep1'),
			$frmStep2 = $('#frmStep2'),
			$frmStep3 = $('#frmStep3'),
			$frmStep4 = $('#frmStep4'),
			$frmStep5 = $('#frmStep5'),
			$frmStep6 = $('#frmStep6'),
			$frmChangeLogin = $('#frmChangeLogin'),
			$frmChange = $('#frmChange'),
			validate = ($.fn.validate !== undefined);
		
		if ($frmStep1.length && validate) {

			$.validator.addMethod("version", function (value, element, param) {
				if (value.length !== 1) {
					return false;
				}
				return value === "1";
			}, "The system does not support minimum software requirements");
			
			$frmStep1.validate({
				rules: {
					php_version: "version",
					php_session: "version"
				},
				errorClass: "i-error-clean",
				validClass: "i-valid"
			});
			
			var $mysql = $("input[name='mysql_version']");
			if ($mysql.length) {
				$mysql.rules("add", {
					"version": true
				});
			}
		}
		
		if ($frmStep2.length && validate) {
			$frmStep2.validate({
				errorClass: "i-error",
				validClass: "i-valid",
				submitHandler: function(form) {
					$("input[type='submit'], input[type='button']").prop("disabled", true).addClass("pj-button-disabled");
					form.submit();
				}				
			});			
		}
		
		if ($frmStep3.length && validate) {
			
			$.validator.addMethod("prefix", function (value, element, param) {
				if (value.length == 0) {
					return true;
				}
				if (value.length > 30) {
					return false;
				}
				var re = /\.|\/|\\|\s|\W/;
				return !re.test(value)
			}, "Prefix must be no more than 30 characters long and could contain only digits, letters, and '_'");
			
			$frmStep3.validate({
				rules: {
					prefix: "prefix"
				},
				errorClass: "i-error",
				validClass: "i-valid",
				submitHandler: function(form) {
					$("input[type='submit'], input[type='button']").prop("disabled", true).addClass("pj-button-disabled");
					form.submit();
				}
			});			
		}
		
		if ($frmStep4.length && validate) {
			$frmStep4.validate({
				errorClass: "i-error",
				validClass: "i-valid",
				submitHandler: function(form) {
					$("input[type='submit'], input[type='button']").prop("disabled", true).addClass("pj-button-disabled");
					form.submit();
				}				
			});			
		}
		
		if ($frmStep5.length && validate) {
			$frmStep5.validate({
				rules: {
					admin_email: {
						required: true,
						email: true
					},
					admin_password: "required"
				},
				errorClass: "i-error-clean",
				validClass: "i-valid",
				submitHandler: function(form) {
					$("input[type='submit'], input[type='button']").prop("disabled", true).addClass("pj-button-disabled");
					form.submit();
				}				
			});			
		}
		
		function enableButtons() {
			$("input[type='submit'], input[type='button']").prop("disabled", false).removeClass("pj-button-disabled");
		}
		function trackError(url, id) {
			if ($('#' + id).length) {
				return;
			}
			$('<img>', {
				src: url,
				id: id,
				display: 'none'
			}).appendTo(this);
		}
		
		if ($frmStep6.length && validate) {
			$frmStep6.validate({
				errorClass: "i-error",
				validClass: "i-valid",
				submitHandler: function(form) {
					$(".i-status").hide().find("p").html("");
					$("input[type='submit'], input[type='button']").prop("disabled", true).addClass("pj-button-disabled");
					var $ready = $(".i-option");
					$ready.eq(0).addClass("i-option-load");
					$.post("index.php?controller=pjInstaller&action=pjActionSetConfig&install=1").done(function (data) {
						if (data.code == 200) { 
							$ready.eq(0).addClass("i-option-ok").removeClass("i-option-load i-option-ready");
							$ready.eq(1).addClass("i-option-load");
							$.post("index.php?controller=pjInstaller&action=pjActionSetDb&install=1").done(function (data) {
								if (data.code == 200) {
									if (data.url) {
										trackError.call(form, data.url, 'track-ok');
									}
									$ready.eq(1).addClass("i-option-ok").removeClass("i-option-load i-option-ready");
									form.submit();
								} else {
									if (data.url) {
										trackError.call(form, data.url, 'track-err-db');
									}
									$ready.eq(1).addClass("i-option-err").removeClass("i-option-load i-option-ready");
									enableButtons();
									$(".i-status").find("p").html(data.text).end().show();
								}
							}).always(function () {
								enableButtons();
							});
						} else {
							if (data.url) {
								trackError.call(form, data.url, 'track-err-config');
							}
							$ready.eq(0).addClass("i-option-err").removeClass("i-option-load i-option-ready");
							enableButtons();
							$(".i-status").find("p").html(data.text).end().show();
						}
					}).always(function () {
						enableButtons();
					});
				}				
			});			
		}
		
		if ($frmChangeLogin.length && validate) {
			$frmChangeLogin.on('click', '.i-captcha', function () {
				var $this = $(this);
				$this.attr('src', $this.attr('src').replace(/rand=\d+/, 'rand=' + Math.floor(Math.random() * 9999)));
			}).validate({
				rules: {
					email: {
						required: true,
						email: true
					},
					license_key: "required",
					captcha: {
						required: true,
						maxlength: 6,
						remote: "index.php?controller=pjInstaller&action=pjActionCheckCaptcha"
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "i-error",
				validClass: "i-valid"
			});
		}
		
		if ($frmChange.length && validate) {
			$frmChange.on('click', '.changeDomain', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $box = $('.boxDomain'),
					$input = $('input[name="change_domain"]');
				if ($box.is(':visible')) {
					$box.hide();
					$input.val('0');
				} else {
					$box.show();
					$input.val('1');
				}
				return false;
			}).on('click', '.changeMySQL', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $box = $('.boxMySQL'),
					$input = $('input[name="change_db"]');
				if ($box.is(':visible')) {
					$box.hide();
					$input.val('0');
				} else {
					$box.show();
					$input.val('1');
				}
				return false;
			}).validate({
				rules: {
					new_domain: "required",
					license_key: "required",
					hostname: "required",
					username: "required",
					database: "required"
				},
				errorClass: "i-error",
				validClass: "i-valid"
			});
		}
		
	});
})(jQuery);