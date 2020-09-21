var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $tabs = $("#tabs"),
			tabs = ($.fn.tabs !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			dialog = ($.fn.dialog !== undefined),
			zinoUpload = ($.fn.zinoUpload !== undefined),
			$upload = $('#upload'),
			$frmUpdateShowID = $("#frmUpdateShowID"),
			$dialogShowID = $("#dialogShowID"),
			$dialogFlagReset = $('#dialogFlagReset'),
			$dialogFlagInfo = $('#dialogFlagInfo');
		
		if ($tabs.length > 0 && tabs) {
			$tabs.tabs({
				select: function (event, ui) {
					switch (ui.index) {
						case 0:
							window.location.href = 'index.php?controller=pjAdminOptions&action=pjActionIndex&tab=0';
							break;
					}
				}
			});
		}
		
		$(".plugin_locale_wrapper_top").scroll(function(){
	        $(".plugin_locale_wrapper_bottom")
	            .scrollLeft($(".plugin_locale_wrapper_top").scrollLeft());
	    });
	    $(".plugin_locale_wrapper_bottom").scroll(function(){
	        $(".plugin_locale_wrapper_top")
	            .scrollLeft($(".plugin_locale_wrapper_bottom").scrollLeft());
	    });
		
	    if ($dialogFlagReset.length && dialog) {
	    	$dialogFlagReset.dialog({
	    		modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: (function () {
					var buttons = {};
					buttons[myLabel.btn_reset] = function () {
						$.post('index.php?controller=pjLocale&action=pjActionFlagReset', {
							'id': $dialogFlagReset.data('id')
						}).done(function (data) {
							if (data && data.status && data.status === 'OK') {
								var content = $grid.datagrid("option", "content");
								$grid.datagrid("load", "index.php?controller=pjLocale&action=pjActionGetLocale", content.column, content.direction, content.page, content.rowCount);
							}
						}).always(function () {
							$dialogFlagReset.dialog('close');
						});
					};
					buttons[myLabel.btn_cancel] = function () {
						$dialogFlagReset.dialog('close');
					};
					return buttons;
				})()
	    	});
	    }
	    
	    if ($dialogFlagInfo.length && dialog) {
	    	$dialogFlagInfo.dialog({
	    		modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				open: function () {
					$dialogFlagInfo.html($dialogFlagInfo.data('text'));
				},
				buttons: (function () {
					var buttons = {};
					buttons[myLabel.btn_close] = function () {
						$dialogFlagInfo.dialog('close');
					};
					return buttons;
				})()
	    	});
	    }
		
	    if ($dialogShowID.length && dialog) {
	    	$dialogShowID.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				width: 400,
				buttons: (function () {
					var buttons = {};
					buttons[myLabel.btnConfirm] = function () {
						$frmUpdateShowID.submit();
					};
					buttons[myLabel.btnCancel] = function () {
						$('#show_id').attr('checked', false);
						$dialogShowID.dialog("close");
					};
					return buttons;
				})()
			});
		}
	    
	    if ($frmUpdateShowID.length) {
	    	$('.pj-show-id-save').click(function(e){
	    		if($("#show_id").is(':checked'))
	    		{
	    			$dialogShowID.dialog('open');
	    		}else{
	    			$frmUpdateShowID.submit();
	    		}
	    	});
	    }
	    
		$("#content").on("change", "input[name='toggle']", function (e) {
			var $this = $(this),
				$tbody = $this.closest("table").find("tbody");
			if ($this.is(":checked")) {
				$tbody.find("input[name='field_id[]']").attr("checked", "checked");
			} else {
				$tbody.find("input[name='field_id[]']").removeAttr("checked");
			}
		}).on("change", "select[name='row_count']", function () {
			var h = window.location.href,
				m = h.match(/row_count=\d+/),
				row_count = $(this).find("option:selected").val();
			if (m !== null) {
				window.location.href = h.replace(/row_count=\d+/, 'row_count=' + row_count);
			} else {
				window.location.href += h.indexOf('?') !== -1 ? '&row_count=' + row_count : '?row_count=' + row_count;
			}
		}).on("click", ".pj-table-sort-up, .pj-table-sort-down", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var h = window.location.href,
				pattern = /column=\w+&direction=(ASC|DESC)/,
				m = h.match(pattern),
				$this = $(this),
				column = $this.data("column"),
				direction = $this.hasClass("pj-table-sort-up") ? "ASC" : "DESC";

			if (m !== null) {
				window.location.href = h.replace(pattern, 'column=' + column + '&direction=' + direction);
			} else {
				window.location.href += h.indexOf('?') !== -1 ? '&column=' + column + '&direction=' + direction : '?column=' + column + '&direction=' + direction;
			}
			return false;
		});
				
		if ($("#grid").length > 0 && datagrid) {
			
			function formatImage (str, obj) {
				var custom = (obj.flag != null && obj.flag !== ""),
					src = custom ? obj.flag : obj.file;
				
				if (str && str.length) {
					return ['<img src="', src, '?', Math.floor(Math.random() * 99999), '" alt="" class="img-locale-flag" />'].join('');
				}
				
				return;
			}
			function formatBtn(str, obj) {
				var upload = ['<button type="button" class="btn-locale btn-locale-upload" title="', myLabel['tooltip_upload'], '"></button>'].join(''),
					reset = ['<button type="button" class="btn-locale btn-locale-reset" title="', myLabel['tooltip_reset'], '"></button>'].join(''),
					custom = (obj.flag != null && obj.flag !== "");
			
				return custom ? reset : upload;
			}
			function formatDefault (str) {
				return '<a href="#" class="pj-status-icon pj-status-' + str + '" style="cursor: ' +  (parseInt(str, 10) === 0 ? 'pointer' : 'default') + '"></a>';
			}
			
			function onBeforeShow(obj) {
				if (parseInt(obj.is_default, 10) === 1) {
					return false;
				}
				return true;
			}
			var x, dOpts = [];
			for (x in myLabel.directions) {
				if (myLabel.directions.hasOwnProperty(x)) {
					dOpts.push({label: myLabel.directions[x], value: x});
				}
			}

			if ($upload.length && zinoUpload) {
				$upload.zinoUpload({
					method: 'POST',
					url: 'index.php?controller=pjLocale&action=pjActionUpload',
					name: 'flag',
					change: function (event, ui) {
						$upload.zinoUpload("option", "data", {
							"id": $upload.data("id"),
							"MAX_FILE_SIZE": pjGrid.maxFileSize
						});
					},
					submit: function (event, ui) {},
					complete: function (event, ui) {
						if (ui.response && ui.response.status) {
							switch (ui.response.status) {
							case 'OK':
								var content = $grid.datagrid("option", "content");
								$grid.datagrid("load", "index.php?controller=pjLocale&action=pjActionGetLocale", content.column, content.direction, content.page, content.rowCount);
								break;
							case 'ERR':
								if ($dialogFlagInfo.length && dialog) {
									$dialogFlagInfo.data('text', ui.response.text).dialog('open');
								}
								break;
							}
						}
						ui.file.closest("form").get(0).reset();
					},
				});
			}

			var $grid = $("#grid").on('click', '.btn-locale-upload', function (e) {
				if ($upload.length && zinoUpload) {
					$upload
						.data('id', $(this).closest('tr').data('object').id)
						.find('input[type="file"]')
						.trigger('click');
				}
			}).on('click', '.btn-locale-reset', function (e) {
				if ($dialogFlagReset.length && dialog) {
					$dialogFlagReset.data('id', $(this).closest('tr').data('object').id).dialog('open');
				}
			}).datagrid({
				buttons: [{type: "delete", url: "index.php?controller=pjLocale&action=pjActionDeleteLocale&id={:id}", beforeShow: onBeforeShow}],
				columns: [{text: myLabel.language, type: "select", sortable: true, editable: true, width: 180, editableWidth: 170, options: pjGrid.languages},
				          {text: myLabel.name, type: "text", sortable: true, editable: true, width: 160, editableWidth: 140},
				          {text: myLabel.flag, type: "text", sortable: false, editable: false, width: 40, align: 'center', renderer: formatImage},
				          {text: '', type: "text", sortable: false, editable: false, renderer: formatBtn},
				          {text: myLabel.dir, type: "select", sortable: true, editable: true, options: dOpts, width: 100, editableWidth: 110},
				          {text: myLabel.is_default, type: "text", sortable: true, editable: false, width: 73, renderer: formatDefault, align: "center"},
				          {text: myLabel.order, type: "text", sortable: true, editable: false, align: "center", width: 55, css: {
				        	  cursor: "move"
				          }}],
				dataUrl: "index.php?controller=pjLocale&action=pjActionGetLocale",
				dataType: "json",
				fields: ['language_iso', 'name', 'file', 'id', 'dir', 'is_default', 'sort'],
				paginator: false,
				saveUrl: "index.php?controller=pjLocale&action=pjActionSaveLocale&id={:id}",
				sortable: true,
				sortableUrl: "index.php?controller=pjLocale&action=pjActionSortLocale"
			});
			
			$(document).on("click", ".btn-add", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				$.post("index.php?controller=pjLocale&action=pjActionSaveLocale").done(function (data) {
					if (data && data.status && data.status === "OK" && data.id) {
						$grid.datagrid("option", "onRender", function () {
							var $td = $("tr[data-id='id_" + data.id + "']").find(".pj-table-cell-editable").filter(":first");
							$td.trigger("click");
							$td.find("select option:not([disabled])").first().attr("selected", "selected");
							$grid.datagrid("option", "onRender", null);
						});
						$grid.datagrid("load", "index.php?controller=pjLocale&action=pjActionGetLocale");
					}
				});
				return false;
			}).on("focus", "select[data-name='language_iso']", function (e) {
				var $this = $(this), values = [];
				if (!$this.data('focused')) {
					$this.closest("tbody").find("select[data-name='language_iso']").not(this).each(function (i) {
						values.push($(this).find("option:selected").val());
					});
					$this.find("option").prop("disabled", false).filter(function (index) {
						return $.inArray(this.value, values) != -1;
					}).prop("disabled", true);
					$this.blur();
				}
				$this.data('focused', true);
			}).on("click", ".pj-status-1", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				return false;
			}).on("click", ".pj-status-0", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				$.post("index.php?controller=pjLocale&action=pjActionSaveDefault", {
					id: $(this).closest("tr").data("object")['id']
				}).done(function (data) {
					$grid.datagrid("load", "index.php?controller=pjLocale&action=pjActionGetLocale");
				});
				return false;
			});
		}
	});
})(jQuery_1_8_2);