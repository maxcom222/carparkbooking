/*!
 * @author Dimitar Ivanov
 */
(function ($, undefined) {
	var PROP_NAME = 'multilang',
		FALSE = false,
		TRUE = true;

	function Multilang() {
		this._defaults = {
			langs: {},
			flagPath: "", //path to flag images
			select: null// callback
		};
		
		this.messages = {
			tooltip: "Select a language by clicking on the corresponding flag and update existing translation."
		};
	}

	Multilang.prototype = {
		_attachMultilang: function (target, settings) {
			if (this._getInst(target)) {
				return FALSE;
			}
			var i, iCnt, $a, $abbr,
				$target = $(target),
				self = this,
				inst = self._newInst($target);

			$.extend(inst.settings, self._defaults, settings);

			$("<a>", {
				"href": "#",
				"title": self.messages.tooltip
			}).addClass("pj-form-langbar-tip pj-selector-langbar-tip").appendTo($target);

			for (i in inst.settings.langs) {
				if (inst.settings.langs.hasOwnProperty(i)) {
					$abbr = $("<abbr>", {
						css: {
							"backgroundImage": ["url(", inst.settings.flagPath, inst.settings.langs[i], ")"].join("")
						}
					});
					$a = $("<a>", {
						"href": "#",
						"data-index": i.replace("_", ""),
						"data-abbr": inst.settings.langs[i]
					}).addClass("pj-form-langbar-item").append($abbr).appendTo($target);
				}
			}
			$target.addClass("pj-form-langbar");
			$target.find(".pj-form-langbar-item[data-index='" + $(".pj-multilang-wrap[style!='display: none']").data("index") + "']").addClass("pj-form-langbar-item-active");

			$target.on("click", ".pj-form-langbar-item", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self._selectMultilang.call(self, target, $(this).data("index"));
				return false;
			}).on("select", $target, function (e) {
				if (inst.settings.select !== null) {
					var $icon = $(".pj-form-langbar-item-active", this).eq(0);
					inst.settings.select.call(target, e, {
						index: $icon.data("index"),
						abbr: $icon.data("abbr")
					});
				}
			}).on("click", ".pj-selector-langbar-tip", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				return false;
			});
			
			if ($.fn.tipsy !== undefined) {
				$(".pj-selector-langbar-tip").tipsy({
					offset: 1,
					opacity: 1,
					html: true
				});
			}
			
			$.data(target, PROP_NAME, inst);
			
		},
		_selectMultilang: function (target, index) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			var $icon = $(".pj-form-langbar-item[data-index='" + index + "']");
			if ($icon.length > 0) {
				$icon
					.addClass("pj-form-langbar-item-active")
					.siblings(".pj-form-langbar-item").removeClass("pj-form-langbar-item-active");
				
				var $wrapper = $(":input[name^='i18n[" + index + "]']").closest(".pj-multilang-wrap");
				$(".pj-multilang-wrap").hide();
				$wrapper.show();
				
				$(target).trigger("select", inst);
			}
			$.data(target, PROP_NAME, inst);
		},
		_optionMultilang: function (target, optName, optValue) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			
			if (typeof optName === 'string') {
				if (arguments.length === 2) {
					return inst.settings[optName];
				} else if (arguments.length === 3) {
					inst.settings[optName] = optValue;
				}
			} else if (typeof optName === 'object') {
				$.extend(inst.settings, optName);
			}
			$.data(target, PROP_NAME, inst);
		},
		_newInst: function(target) {
			var id = target[0].id.replace(/([^A-Za-z0-9_-])/g, '\\\\$1');
			return {
				id: id, 
				input: target, 
				uid: Math.floor(Math.random() * 99999999),
				settings: {}
			}; 
		},
		_getInst: function(target) {
			try {
				return $.data(target, PROP_NAME);
			}
			catch (err) {
				throw 'Missing instance data for this multilang';
			}
		}
	};

	$.fn.multilang = function (options) {
		
		var otherArgs = Array.prototype.slice.call(arguments, 1);
		if (typeof options == 'string' && options == 'isDisabled') {
			return $.multilang['_' + options + 'Multilang'].apply($.multilang, [this[0]].concat(otherArgs));
		}
		
		if (options == 'option' && arguments.length == 2 && typeof arguments[1] == 'string') {
			return $.multilang['_' + options + 'Multilang'].apply($.multilang, [this[0]].concat(otherArgs));
		}
		
		return this.each(function() {
			typeof options == 'string' ?
				$.multilang['_' + options + 'Multilang'].apply($.multilang, [this].concat(otherArgs)) :
				$.multilang._attachMultilang(this, options);
		});
	};
	
	$.multilang = new Multilang(); // singleton instance
	$.multilang.version = "0.1";
})(jQuery);