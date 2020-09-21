/*!
 * ZinoUI v1.5 (http://zinoui.com)
 * Copyright 2012-2015 Dimitar Ivanov. All Rights Reserved.
 * 
 * Zino UI commercial licenses may be obtained at http://zinoui.com/license
 * If you do not own a commercial license, this file shall be governed by the
 * GNU General Public License (GPL) version 3.
 * For GPL requirements, please review: http://www.gnu.org/copyleft/gpl.html
 */
(function ($, undefined) {
	"use strict";
	
	var PROP_NAME = 'upload',
		FALSE = false,
		TRUE = true;

	function Upload() {
		this._defaults = {
			url: "upload.php",
			method: "PUT",
			name: "file",
			label: "Upload",
			data: {},
			multiple: false,
			autoSubmit: true,
			change: null,
			submit: null,
			complete: null,
			progress: null
		};
	}
	
	function FData() {
		this.boundary = "------ZinoUI" + Math.ceil(Math.random() * 9999999);
		this.data = [];
	}
	FData.prototype = {
		append: function (key, value) {
			this.data.push([key, value]);
			return this;
		},
		toString: function () {
			var i, iCnt, file,
				eol = "\r\n",
				boundary = this.boundary,
				stack = [];

			for (i = 0, iCnt = this.data.length; i < iCnt; i++) {
				stack.push(["--", boundary, eol].join(""));
				if (this.data[i][1].name) {
					file = this.data[i][1];
					stack.push(['Content-Disposition: form-data; name="', this.data[i][0], '"; filename="', file.name, '"', eol].join(""));
					stack.push(["Content-Type: ", file.type, eol, eol].join(""));
					stack.push([file.getAsBinary(), eol].join(""));
				} else {
					stack.push(['Content-Disposition: form-data; name="', this.data[i][0], '";', eol, eol].join(""));
					stack.push([this.data[i][1], eol].join(""));
				}
			}
			stack.push(["--", boundary, "--"].join(""));
			
			return stack.join("");
		}
	};
	
	Upload.prototype = {
		_attachUpload: function (target, settings) {
			if (this._getInst(target)) {
				return FALSE;
			}
			var $target = $(target),
				that = this,
				inst = this._newInst($target);

			$.extend(inst.settings, this._defaults, settings);

			$target.addClass("zui-upload").hover(function () {
				$(this).addClass("zui-upload-hover");
			}, function () {
				$(this).removeClass("zui-upload-hover");
			});
			
			if (inst.settings.label.length > 0) {
				inst.$label = $("<span>").addClass("zui-upload-label").text(inst.settings.label);
				inst.$label.appendTo($target);
			}
			
			inst.$form = $(['<form method="', inst.settings.method, '" action="', inst.settings.url, '" enctype="multipart/form-data" encoding="multipart/form-data"><input name="', inst.settings.name, '"', (inst.settings.multiple ? ' multiple="multiple"' : ""), ' type="file" class="zui-upload-file" /></form>'].join(""));
			inst.$form
				.addClass("zui-upload-form")
				.bind("submit", function (e) {
					if (!window.FormData || !window.FileList) {
						inst.$iframe.load(function () {
							inst.$file.removeAttr("disabled");
							$target.trigger("uploadcomplete", {
								file: inst.$file,
								response: $(this).contents().find('html body').text()
							});
							inst.$iframe.unbind();
						});
						$(this).attr("target", "zui-upload-iframe-" + inst.uid);
					} else {
						var fd = window.FormData ? new FormData() : new FData();
						
						if (window.FileList) {
							$.each(inst.$file[0].files, function (i, file) {
								fd.append(inst.settings.name, file);
							});
						}/* else {
							for (var i = 0, iCnt = inst.fileList.length; i < iCnt; i++) {
								fd.append(inst.settings.name, inst.fileList[i].name);
							}
						}*/
						
						for (var x in inst.settings.data) {
							if (inst.settings.data.hasOwnProperty(x)) {
								fd.append(x, inst.settings.data[x]);
							}
						}
						
						$.ajax({
							xhr: function () {
						        var xhr = new window.XMLHttpRequest();
						        if (xhr.upload) {
							        xhr.upload.addEventListener("progress", function (event) {
							        	$target.trigger("uploadprogress", {
											file: inst.$file,
											event: event
										});
							        }, false);
						        }
						        xhr.addEventListener("progress", function (event) {
						        	$target.trigger("uploadprogress", {
										file: inst.$file,
										event: event
									});
						        }, false);
						        return xhr;
						    },
							url: inst.settings.url,
							data: fd,
							cache: false,
							contentType: false,
							processData: false,
							type: inst.settings.method
						}).done(function (data) {
							inst.$file.removeAttr("disabled");
							$target.trigger("uploadcomplete", {
								file: inst.$file,
								response: data
							});
						});
						return false;
					}
					e.stopPropagation();
				})
				.appendTo($target)
			;
			
			inst.$file = inst.$form.find("input[type='file']");
			
            inst.$file.bind("change", function () {
            	var result = $target.trigger("uploadchange", {
            		file: $(this)
            	}).data("result");
            	
            	if (result === FALSE) {
            		return;
            	}
            	
            	inst.fileList = this.files;
            	
            	if (inst.settings.autoSubmit) {
            		that._processUpload.call(that, target);
            	}
            });
			
            // fallback for old browsers
            if (!window.FormData || !window.FileList) {
				inst.$iframe = $('<iframe name="zui-upload-iframe-'+inst.uid+'" id="zui-upload-iframe-'+inst.uid+'"></iframe>')
					.attr("src", "javascript:false")
					.addClass("zui-upload-iframe")
					.hide()
					.appendTo(target)
				;
				
				for (var x in inst.settings.data) {
					if (inst.settings.data.hasOwnProperty(x)) {
						$("<input>", {
							"type": "hidden",
							"name": x,
							"value": inst.settings.data[x]
						}).appendTo(inst.$form);
					}
				}
            }
			
			$target
				.bind("uploadchange", function (event, ui) {
					$(this).data("result", TRUE);
					if (inst.settings.change !== null) {
						inst.settings.change.call(target, event, ui);
					}
				})
				.bind("uploadsubmit", function (event, ui) {
					$(this).data("result", TRUE);
					if (inst.settings.submit !== null) {
						inst.settings.submit.call(target, event, ui);
					}
				})
				.bind("uploadcomplete", function (event, ui) {
					if (inst.settings.complete !== null) {
						inst.settings.complete.call(target, event, ui);
					}
				})
				.bind("uploadprogress", function (event, ui) {
					if (inst.settings.progress !== null) {
						inst.settings.progress.call(target, event, ui);
					}
				})
			;
			
			$.data(target, PROP_NAME, inst);
		},
		_processUpload: function (target) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			var result = $(target).trigger("uploadsubmit", {
				file: inst.$file
			}).data("result");
			
			if (result === FALSE) {
				return;
			}
			inst.$form.submit();
			inst.$file.attr("disabled", "disabled");
		},
		_destroyUpload: function (target) {
			var inst = this._getInst(target),
				$target = $(target);
			if (!inst) {
				return FALSE;
			}
			
			inst.$form.remove();
			inst.$iframe.remove();
			$target.removeClass("zui-upload").find(".zui-upload-label").remove();
			$.data(target, PROP_NAME, FALSE);
		},
		_optionUpload: function (target, opt) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			if (typeof opt === "string") {
				if (arguments[2]) {
					inst.settings[opt] = arguments[2];
				} else {
					return inst.settings[opt];
				}
			} else if (typeof opt === "object") {
				$.extend(inst.settings, opt);
			}
			$.data(target, PROP_NAME, inst);
		},
		_newInst: function(target) {
			var id = target[0].id.replace(/([^A-Za-z0-9_-])/g, '\\\\$1');
			return {
				id: id, 
				input: target, 
				uid: Math.floor(Math.random() * 99999999),
				isDisabled: FALSE,
				settings: {}
			}; 
		},
		_getInst: function(target) {
			try {
				return $.data(target, PROP_NAME);
			}
			catch (err) {
				throw 'Missing instance data for this upload';
			}
		}
	};
	
	$.fn.zinoUpload = function (options) {
		
		var otherArgs = Array.prototype.slice.call(arguments, 1);
		if (typeof options == 'string' && options == 'isDisabled') {
			return $.zinoUpload['_' + options + 'Upload'].apply($.zinoUpload, [this[0]].concat(otherArgs));
		}
		
		if (options == 'option' && arguments.length == 2 && typeof arguments[1] == 'string') {
			return $.zinoUpload['_' + options + 'Upload'].apply($.zinoUpload, [this[0]].concat(otherArgs));
		}
		
		return this.each(function() {
			typeof options == 'string' ?
				$.zinoUpload['_' + options + 'Upload'].apply($.zinoUpload, [this].concat(otherArgs)) :
				$.zinoUpload._attachUpload(this, options);
		});
	};
	
	$.zinoUpload = new Upload();
	$.zinoUpload.version = "1.5";
})(jQuery);