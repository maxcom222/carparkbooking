/*!
 * Car Park Booking Script v2.0
 * http://www.phpjabbers.com/car-park-booking/
 * 
 * Copyright 2014, StivaSoft Ltd.
 * 
 */
(function (window, undefined){
	"use strict";
	
	pjQ.$.ajaxSetup({
		xhrFields: {
			withCredentials: true
		}
	});
	
	var document = window.document,
		validate = (pjQ.$.fn.validate !== undefined),
		datepicker = (pjQ.$.fn.datepicker !== undefined),
		dialog = (pjQ.$.fn.dialog !== undefined),
		routes = [
		          	{pattern: /^#!\/Search$/, eventName: "loadSearch"},
		          	{pattern: /^#!\/Spaces$/, eventName: "loadSpaces"},
		          	{pattern: /^#!\/Spaces\/sortby:(\S+)?$/, eventName: "loadSpaces"},
		          	{pattern: /^#!\/Extras$/, eventName: "loadExtras"},
		          	{pattern: /^#!\/Extras\/space_id:(\d+)?$/, eventName: "loadExtras"},
		          	{pattern: /^#!\/Checkout$/, eventName: "loadCheckout"},
		          	{pattern: /^#!\/Checkout\/space_id:(\d+)?$/, eventName: "loadCheckout"},
		          	{pattern: /^#!\/Preview$/, eventName: "loadPreview"}
		          ];
	
	function log() {
		if (window.console && window.console.log) {
			for (var x in arguments) {
				if (arguments.hasOwnProperty(x)) {
					window.console.log(arguments[x]);
				}
			}
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	function hashBang(value) {
		if (value !== undefined && value.match(/^#!\//) !== null) {
			if (window.location.hash == value) {
				return false;
			}
			window.location.hash = value;
			return true;
		}
		
		return false;
	}
	
	function onHashChange() {
		var i, iCnt, m;
		for (i = 0, iCnt = routes.length; i < iCnt; i++) {
			m = window.location.hash.match(routes[i].pattern);
			if (m !== null) {
				pjQ.$(window).trigger(routes[i].eventName, m.slice(1));
				break;
			}
		}
		if (m === null) {
			pjQ.$(window).trigger("loadEvents");
		}
	}
	pjQ.$(window).on("hashchange", function (e) {
    	onHashChange.call(null);
    });
	
	function CarParkBooking(opts) {
		if (!(this instanceof CarParkBooking)) {
			return new CarParkBooking(opts);
		}
				
		this.reset.call(this);
		this.init.call(this, opts);
		
		return this;
	}
	
	CarParkBooking.inObject = function (val, obj) {
		var key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) {
				if (obj[key] == val) {
					return true;
				}
			}
		}
		return false;
	};
	
	CarParkBooking.size = function(obj) {
		var key,
			size = 0;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) {
				size += 1;
			}
		}
		return size;
	};
	
	CarParkBooking.prototype = {
		reset: function () {
			this.$container = null;
			this.container = null;
			this.opts = {};
			
			this.sortby = null;
			this.space_id = null;
			return this;
		},
		disableButtons: function () {
			var $el;
			this.$container.find(".pjCpbSelectorButton").each(function (i, el) {
				$el = pjQ.$(el).attr("disabled", "disabled");
			});
		},
		enableButtons: function () {
			this.$container.find(".pjCpbSelectorButton").removeAttr("disabled");
		},
		
		init: function (opts) {
			var self = this;
			this.opts = opts;
			this.container = document.getElementById("cpContainer_" + this.opts.index);
			this.$container = pjQ.$(this.container);
			
			this.$container.on("click.cp", ".cpSelectorLocale", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var locale = pjQ.$(this).data("id");
				self.opts.locale = locale;
				pjQ.$(this).addClass("cpLocaleFocus").parent().parent().find("a.cpSelectorLocale").not(this).removeClass("cpLocaleFocus");
				
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionLocale", "&session_id=", self.opts.session_id].join(""), {
					"locale_id": locale
				}).done(function (data) {					
					if(!hashBang("#!/Search"))
					{
						self.loadSearch.call(self);
					}
				}).fail(function () {
					log("Deferred is rejected");
				});
				return false;
			}).on("focusin.cp", ".pjCpbDateTimePicker", function (e) {
				var minDateTime, maxDateTime,
					$this = pjQ.$(this),
					custom = {},
					o = {
						firstDay: $this.attr("data-fday"),
						dateFormat: $this.attr("data-dformat"),
						timeFormat: $this.attr("data-tformat"),
						dayNames: ($this.data("day")).split(","),
					    monthNames: ($this.data("months")).split(","),
					    monthNamesShort: ($this.data("shortmonths")).split(","),
					    dayNamesMin: ($this.data("daymin")).split(","),
					    timeText: $this.attr("data-timeText"),
					    hourText: $this.attr("data-hourText"),
					    minuteText: $this.attr("data-minuteText"),
					    currentText: $this.attr("data-currentText"),
					    closeText: $this.attr("data-closeText"),
						stepMinute: 5,
						minDateTime: 0,
						controlType: 'select',
						beforeShow: function(input, inst) {
							pjQ.$('#ui-datepicker-div').addClass("pjCpjQueryUI");
						},
						onClose: function (dateTimeText){
						}
				};
				switch ($this.attr("name")) {
				case "from":
					if(pjQ.$(".pjCpbDateTimePicker[name='to']").val() != '')
					{
						maxDateTime = pjQ.$(".pjCpbDateTimePicker[name='to']").datetimepicker({
							firstDay: $this.attr("data-fday"),
							dateFormat: $this.attr("data-dformat"),
							timeFormat: $this.attr("data-tformat")
						}).datetimepicker("getDate");
						pjQ.$(".pjCpbDateTimePicker[name='to']").datepicker("destroy").removeAttr("id");
						if (maxDateTime !== null) {
							custom.maxDateTime = maxDateTime;
							custom.minDateTime = 0;
						}
					}
					break;
				case "to":
					if(pjQ.$(".pjCpbDateTimePicker[name='from']").val() != '')
					{
						minDateTime = pjQ.$(".pjCpbDateTimePicker[name='from']").datetimepicker({
							firstDay: $this.attr("data-fday"),
							dateFormat: $this.attr("data-dformat"),
							timeFormat: $this.attr("data-tformat")
						}).datetimepicker("getDate");
						pjQ.$(".pjCpbDateTimePicker[name='from']").datepicker("destroy").removeAttr("id");
						if (minDateTime !== null) {
							custom.minDateTime = minDateTime;
						}
					}
					break;
				}
				pjQ.$(this).datetimepicker(pjQ.$.extend(o, custom));
			}).on("click.cp", ".pjCpbDateTimeIcon", function (e) {
				var $dp = pjQ.$(this).siblings("input[type='text']");
				if ($dp.hasClass("hasDatepicker")) {
					$dp.datepicker("show");
				} else {
					$dp.trigger("focusin").datepicker("show");
				}
			}).on("click.cp", ".pjCpbChangeDates", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Search");
			}).on("change.cp", ".pjCpbSortBy", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Spaces/sortby:" + pjQ.$(this).val());
			}).on("click.cp", ".pjCpbBtnBookNow", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.space_id = pjQ.$(this).attr('data-id');
				if(pjQ.$(this).attr('data-extras') == '0')
				{
					hashBang("#!/Checkout/space_id:" + self.space_id);
				}else{
					hashBang("#!/Extras/space_id:" + self.space_id);
				}
			}).on("click.cp", ".pjCpbChangeSpace", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Spaces");
			}).on("click.cp", ".pjCpbBtnAddExtra", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.handleExtras.call(self, pjQ.$(this).attr('data-id'), 1);
			}).on("change.cp", ".pjCpbExtraCount", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.handleExtras.call(self, pjQ.$(this).attr('data-id'), pjQ.$(this).val());
			}).on("click.cp", ".pjCpbBtnRemoveExtra", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.handleExtras.call(self, pjQ.$(this).attr('data-id'), 0);
			}).on("click.cp", ".pjCpbBackToSpaces", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				hashBang("#!/Spaces");
			}).on("click.cp", ".pjCpbProceedPayment", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				hashBang("#!/Checkout");
			}).on("click.cp", ".pjCpbChangeExtras", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Extras/space_id:" + pjQ.$(this).attr('data-space_id'));
			}).on("click.cp", ".pjCpbBtnAddPromoCode", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $frmCheckout = pjQ.$('#pjCpbCheckoutform_' + self.opts.index),
					code = $frmCheckout.find("input[name='code']").val();
				if(code != '')
				{
					self.disableButtons.call(self);
					pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionAddPromo", "&session_id=", self.opts.session_id, "&code=", code].join("")).done(function (data) {
						if(data != '100')
						{
							pjQ.$('#pjCpbPromoContainer_' + self.opts.index).html(data).show();
							self.loadPrices.call(self);
						}else{
							self.enableButtons.call(self);
							pjQ.$('#pjCpbPromoContainer_' + self.opts.index).next().show().delay(2000).fadeOut();
						}
					}).fail(function () {
						self.enableButtons.call(self);
					});
				}
			}).on("click.cp", ".pjCpbBtnRemovePromoCode", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionRemovePromo", "&session_id=", self.opts.session_id].join("")).done(function (data) {
					if(data.code == '200')
					{
						pjQ.$('#pjCpbPromoContainer_' + self.opts.index).hide();
						self.loadPrices.call(self);
					}
				}).fail(function () {
					self.enableButtons.call(self);
				});
			}).on("change.tb", "select[name='payment_method']", function () {
				self.$container.find(".pjCpbCcWrap").hide();
				self.$container.find(".pjCpbBankWrap").hide();
				switch (pjQ.$("option:selected", this).val()) {
				case 'creditcard':
					self.$container.find(".pjCpbCcWrap").show();
					break;
				case 'bank':
					self.$container.find(".pjCpbBankWrap").show();
					break;
				}
			}).on("click.cp", ".pjCpbBackToExtras", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				if(pjQ.$(this).attr('data-extras') == '0')
				{
					hashBang("#!/Spaces");
				}else{
					hashBang("#!/Extras/space_id:" + pjQ.$(this).attr('data-space_id'));
				}
			}).on("click.cp", ".pjCpbBackToCheckout", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.disableButtons.call(self);
				hashBang("#!/Checkout");
			}).on("click.cp", ".pjCpbBtnStartOver", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Search");
			});
			
			pjQ.$(window).on("loadSearch", this.container, function (e) {
				self.loadSearch.call(self);
			}).on("loadSpaces", this.container, function (e) {
				switch (arguments.length) {
					case 1:
						break;
					case 2:
						self.sortby = arguments[1];
						break;
				}
				self.loadSpaces.call(self);
			}).on("loadExtras", this.container, function (e) {
				switch (arguments.length) {
					case 1:
						break;
					case 2:
						self.space_id = arguments[1];
						break;
				}
				self.loadExtras.call(self);
			}).on("loadCheckout", this.container, function (e) {
				switch (arguments.length) {
					case 1:
						break;
					case 2:
						self.space_id = arguments[1];
					break;
				}
				self.loadCheckout.call(self);
			}).on("loadPreview", this.container, function (e) {
				self.loadPreview.call(self);
			});
			
			if (window.location.hash.length === 0) {
				this.loadSearch.call(this);
			} else {
				onHashChange.call(null);
			}
		},
		
		handleExtras: function(extra_id, cnt){
			var self = this,
				index = this.opts.index,
				params = 	{
						"extra_id": extra_id,
						"cnt": cnt
					};
			self.disableButtons.call(self);
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionHandleExtras", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.loadExtras.call(self);
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadPrices: function(){
			var self = this,
				index = this.opts.index;
			self.disableButtons.call(self);
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionGetPrices", "&session_id=", self.opts.session_id].join("")).done(function (data) {
				pjQ.$('#pjCpbPriceContainer_' + self.opts.index).html(data);
				self.enableButtons.call(self);
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadSearch: function () {
			var self = this,
				index = this.opts.index,
				params = 	{
								"theme": this.opts.theme,
								"locale": this.opts.locale,
								"hide": this.opts.hide,
								"index": this.opts.index
							};
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionSearch", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
				
				var $frmSearch = pjQ.$('#pjCpSearchForm_' + self.opts.index);
				
				if($frmSearch.length > 0 && validate)
				{
					$frmSearch.validate({
						onkeyup: false,
						errorElement: 'li',
						errorPlacement: function (error, element) {
							error.appendTo(element.parent().next().find('ul'));
						},
			            highlight: function(ele, errorClass, validClass) {
			            	var element = pjQ.$(ele);
			            	element.parent().parent().parent().parent().addClass('has-error');
			            },
			            unhighlight: function(ele, errorClass, validClass) {
			            	var element = pjQ.$(ele);
			            	element.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
			            },
						submitHandler: function (form) {
							self.disableButtons.call(self);
							pjQ.$.post([self.opts.folder, "index.php?controller=pjFront&action=pjActionSetDateTimes", "&session_id=", self.opts.session_id].join(""), $frmSearch.serialize()).done(function (data) {
								if(data.status == 'OK')
								{
									hashBang("#!/Spaces");
								}
							}).fail(function () {
								log("Deferred is rejected");
							});
							return false;
						}
					});
				}
				
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadSpaces: function () {
			var self = this,
				index = this.opts.index,
				params = 	{
								"theme": this.opts.theme,
								"locale": this.opts.locale,
								"hide": this.opts.hide,
								"index": this.opts.index
							};
			if (self.sortby !== null) {
				params = pjQ.$.extend(params, {
					"sortby": self.sortby
				});
			}
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionSpaces", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadExtras: function () {
			var self = this,
				index = this.opts.index,
				params = 	{
								"theme": this.opts.theme,
								"locale": this.opts.locale,
								"hide": this.opts.hide,
								"index": this.opts.index
							};
			if (self.space_id !== null) {
				params = pjQ.$.extend(params, {
					"space_id": self.space_id
				});
			}
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionExtras", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
			}).fail(function () {
				self.enableButtons.call(self);
			});
		},
		loadCheckout: function () {			
			var self = this,
				index = this.opts.index,
				params = 	{
							"locale": this.opts.locale,
							"theme": this.opts.theme,
							"hide": this.opts.hide,
							"index": this.opts.index
						};
			if (self.space_id !== null) {
				params = pjQ.$.extend(params, {
					"space_id": self.space_id
				});
			}
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionCheckout", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
				pjQ.$('.modal-dialog').css("z-index", "9999"); 
				if (validate) 
				{
					var $form = pjQ.$('#pjCpbCheckoutform_'+ self.opts.index);
					$form.validate({
						rules: {
							"captcha": {
								remote: self.opts.folder + "index.php?controller=pjFront&action=pjActionCheckCaptcha&session_id=" + self.opts.session_id
							}
						},
						messages: {
							"captcha": {
								remote: $form.find("input[name='captcha']").attr('data-msg-captcha')
							}
						},
						onkeyup: false,
						errorElement: 'li',
						errorPlacement: function (error, element) {
							if(element.attr('name') == 'terms')
							{
								error.appendTo(element.siblings().find('ul'));
							}else{
								error.appendTo(element.next().find('ul'));
							}
						},
			            highlight: function(ele, errorClass, validClass) {
			            	var element = pjQ.$(ele);
			            	if(element.attr('name') == 'captcha')
							{
								element.parent().parent().parent().addClass('has-error');
							}else{
								element.parent().addClass('has-error');
							}
			            },
			            unhighlight: function(ele, errorClass, validClass) {
			            	var element = pjQ.$(ele);
			            	if(element.attr('name') == 'captcha')
							{
								element.parent().parent().parent().removeClass('has-error').addClass('has-success');
							}else{
								element.parent().removeClass('has-error').addClass('has-success');
							}
			            },
						submitHandler: function (form) {
							self.disableButtons.call(self);
							var $form = pjQ.$(form);
							pjQ.$.post([self.opts.folder, "index.php?controller=pjFront&action=pjActionCheckout", "&session_id=", self.opts.session_id].join(""), $form.serialize()).done(function (data) {
								if (data.status == "OK") {
									hashBang("#!/Preview");
								}
							}).fail(function () {
								self.enableButtons.call(self);
							});
							return false;
						}
					});
				}
			});
		},
		loadPreview: function () {
			var self = this,
				index = this.opts.index,
				params = 	{
					"locale": this.opts.locale,
					"theme": this.opts.theme,
					"hide": this.opts.hide,
					"index": this.opts.index
				};
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionPreview", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
				if (validate) {
					pjQ.$('#pjCpbPreviewForm_'+ self.opts.index).validate({
						rules: {},
						onkeyup: false,
						onclick: false,
						onfocusout: false,
						submitHandler: function (form) {
							self.disableButtons.call(self);
							var $form = pjQ.$(form);
							pjQ.$.post([self.opts.folder, "index.php?controller=pjFront&action=pjActionSaveBooking", "&session_id=", self.opts.session_id].join(""), $form.serialize()).done(function (data) {
								if (data.code == "200") {
									self.getPaymentForm.call(self, data);
								} else if (data.code == "119") {
									self.enableButtons.call(self);
								}
							}).fail(function () {
								self.enableButtons.call(self);
							});
							return false;
						}
					});
				}
			});
		},
		getPaymentForm: function(obj){
			var self = this,
				index = this.opts.index;
			var params = {
					"locale": this.opts.locale,
					"hide": this.opts.hide,
					"index": this.opts.index,
					"booking_id": obj.booking_id, 
					"payment_method": obj.payment,
					"theme": this.opts.theme,
				};
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionGetPaymentForm", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
				switch (obj.payment) {
					case 'paypal':
						self.$container.find("form[name='cpPaypal']").trigger('submit');
						break;
					case 'authorize':
						self.$container.find("form[name='cpAuthorize']").trigger('submit');
						break;
					case 'creditcard':
					case 'bank':
					case 'cash':
						break;
				}
			}).fail(function () {
				log("Deferred is rejected");
			});
		}
	};
	
	window.CarParkBooking = CarParkBooking;	
})(window);