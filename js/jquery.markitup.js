(function($) {
	$.fn.markItUp = function(settings, extraSettings) {
		var options, ctrlKey, shiftKey, altKey;
		ctrlKey =  shiftKey =  altKey = false;

		options = {	nameSpace:				"",
					previewIFrame:			true,
					previewIFrameRefresh:	true,
					previewBaseUrl:			"",
					previewCharset:			"utf-8",
					previewCssPath:			"",
					previewBodyId:			"",
					previewBodyClassName:	"",
					previewParserPath:		"",
					previewParserVar:		"data",
					beforeInsert:			"",
					afterInsert:			"",
					onEnter:				{},
					onShiftEnter:			{},
					onCtrlEnter:			{},
					onTab:					{},
					markupSet:			[	{ /* ... */ } ]
				};
		$.extend(options, settings, extraSettings);

		return this.each(function() {
			var $$, textArea, levels, iFrame, scrollPosition, caretPosition, caretOffset,
				clicked, hash, header, footer, win;
			$$ = $(this);
			textArea = this;
			levels = [];
			iFrame = false;
			scrollPosition = 0;
			caretPosition = 0;
			caretOffset = -1;

			
			function init() {
				$$.wrap('<span class="'+options.nameSpace+'"></span>');
				$$.wrap('<div id="'+($$.attr("id")||"")+'" class="markItUp"></div>');
				$$.wrap('<div class="markItUpContainer"></div>');
				$$.attr("id", "").addClass("markItUpEditor");

				header = $('<div class="markItUpHeader"></div>').insertBefore($$);
				$(dropMenus(options.markupSet)).appendTo(header);

				resizeHandle = $('<div class="markItUpResizeHandle"></div>')
					.insertAfter($$)
					.bind("mousedown", function(e) {
						var h = $$.height(), y = e.clientY, mouseMove, mouseUp;
						mouseMove = function(e) {
							$$.css("height", Math.max(20, e.clientY+h-y)+"px");
						};
						mouseUp = function(e) {
							$("html").unbind("mousemove", mouseMove).unbind("mouseup", mouseUp);
						};
						$("html").bind("mousemove", mouseMove).bind("mouseup", mouseUp);
				});
				footer = $('<div class="markItUpFooter"></div>').insertAfter($$);
				footer.append(resizeHandle);

				
				$$.keydown(keyPressed).keyup(keyPressed);

				
				$$.bind("insertion", function(e, settings) {
					if (settings.target !== false) {
						get();
					}
					if (textArea === $.markItUp.focused) {
						markup(settings);
					}
				});

				
				$$.focus(function() {
				   $.markItUp.focused = this;
				});
			}
			
			
			function dropMenus(markupSet) {
				var ul = $("<ul></ul>"), i = 0;
				$("li:hover > ul", ul).css("display", "block");
				$(markupSet).each(function() {
					var button = this, t = "", title, li, j;
					title = (button.key) ? " [Ctrl+"+button.key+"]" : "";
					if (button.separator) {
						li = $('<li class="markItUpSeparator">'+(button.separator||"")+'</li>').appendTo(ul);
					} else {
						i++;
						for (j = levels.length -1; j >= 0; j--) {
							t += levels[j]+"-";
						}
						li = $('<li class="'+(button.className||"")+' markItUpButton markItUpButton'+t+(i)+'"><a href="#" accesskey="'+(button.key||"")+'" title="'+(button.name+title||"")+'">'+(button.name||"")+'</a></li>')
						.click(function(e) { 
							if (button.call) {
								eval(button.call)();
							}
							markup(button);
							return false;
						}).hover(function() {
								$("> ul", this).show();
							}, function() {
								$("> ul", this).hide();
							}
						).appendTo(ul);
						if (button.dropMenu) {
							levels.push(i);
							$(li).addClass("markItUpDropMenu").append(dropMenus(button.dropMenu));
						}
					}
				}); 
				levels.pop();
				return ul;
			}

			
			function magicMarkups(string) {
				if (string) {
					string = string.toString();
			
					string = string.replace(/\(\!\((.*?)\)\!\)/gm,
						function(x, a) {
							var b = a.split("|!|");
							if (altKey === true) {
								return (b[1] !== undefined) ?  b[1] : b[0];
							} else {
								return (b[1] === undefined) ? "" : b[0];
							}
						});
			
					string = string.replace(/\[\!\[(.*?)\]\!\]/gm, 
						function (a) {
							var b = a.replace(/(\[\!\[|\]\!\])/gm, "").split(":!:");
							return prompt(b[0], (b[1]) ? b[1] : "")||""; 
						});
					return string;
				}
				return "";
			}

			
			function prepare(action) {
				if ($.isFunction(action)) { action = action(hash); }
				return magicMarkups(action);
			}

			
			function build(string) {
				openWith 	= prepare(clicked.openWith);
				placeHolder = prepare(clicked.placeHolder);
				replaceWith = prepare(clicked.replaceWith);
				closeWith 	= prepare(clicked.closeWith);

				if (replaceWith !== "") {
					block = openWith + replaceWith + closeWith;
				} else if (selection === "" && placeHolder !== "") {
					block = openWith + placeHolder + closeWith;
				} else {
					block = openWith + (string||selection) + closeWith;
				}

				return {	block:		block, 
							openWith:	openWith, 
							replaceWith:replaceWith, 
							placeHolder:placeHolder,
							closeWith:	closeWith
						};
			}

			
			function markup(button) {
				var len, j, n, i;
				hash = clicked = button;
				get();

				$.extend(hash, { line:"", textarea:textArea, selection:(selection||""), placeHolder:button.placeHolder, caretPosition:caretPosition, scrollPosition:scrollPosition } );

				
				prepare(options.beforeInsert);
				prepare(clicked.beforeInsert);
				if (ctrlKey === true && shiftKey === true) {
					prepare(clicked.beforeMultiInsert);
				}

				$.extend(hash, { line:1 }); 

				if (ctrlKey === true && shiftKey === true) {
					lines = selection.split((($.browser.mozilla) ? "\n" : "\r\n"));
					for (j = 0, n = lines.length, i = 0; i < n; i++) {
						if ($.trim(lines[i]) !== "") {
							$.extend(hash, { line:++j, selection:lines[i] } );
							lines[i] = build(lines[i]).block;
						} else {
							lines[i] = "";
						}
					}
					string = { block:lines.join("\n")};
					start = caretPosition;
					len = string.block.length + (($.browser.opera) ? n : 0);
				} else if (ctrlKey === true) {
					string = build(selection);
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
				} else if (shiftKey === true) {
					string = build(selection);
					start = caretPosition;
					len = string.block.length;
				} else {
					string = build(selection);
					start = caretPosition + string.block.length ;
					len = 0;
				}
				if ((selection === "" && string.replaceWith === "")) { 
					if ($.browser.opera) { 
						caretPosition += (string.block.length - string.block.replace(/^\n*/g, "").length);
					}
					start = caretPosition + string.openWith.length;
					len = string.block.length - string.openWith.length - string.closeWith.length;
					caretOffset = $$.val().substring(caretPosition,  $$.val().length).length;
				}

				$.extend(hash, { caretPosition:caretPosition, scrollPosition:scrollPosition } ); 

				
				if (string.block !== selection) {
					insert(string.block);
					set(start, len);
				}
				get();

				$.extend(hash, { line:"", selection:selection }); 

				
				if (ctrlKey === true && shiftKey === true) {
					prepare(clicked.afterMultiInsert);
				}
				prepare(clicked.afterInsert);
				prepare(options.afterInsert);

				
				if (iFrame && win && options.previewIFrameRefresh) { refreshPeview(); }
			}

			
			function insert(block) {
				if (document.selection) {
					var newSelection = document.selection.createRange();
					newSelection.text = block;
				} else { 
					$$.val($$.val().substring(0, caretPosition)	+ block + $$.val().substring(caretPosition + selection.length, $$.val().length));
				}
			}


			function set(start, len) {
				if (textArea.createTextRange){
					range = textArea.createTextRange();
					range.collapse(true);
					range.moveStart("character", start); 
					range.moveEnd("character", len); 
					range.select();
				} else if (textArea.setSelectionRange ){
					textArea.setSelectionRange(start, start + len);
				}
				textArea.scrollTop = scrollPosition;
				textArea.focus();
			}


			function get() {
				textArea.focus();
				scrollPosition = textArea.scrollTop;
				if (document.selection) {
					selection = document.selection.createRange().text;
					if ($.browser.msie) { 
						var range = document.selection.createRange(), rangeCopy = range.duplicate();
						rangeCopy.moveToElementText(textArea);
						caretPosition = -1;
						while(rangeCopy.inRange(range)) { 
							rangeCopy.moveStart("character");
							caretPosition ++;
						}
					} else { 
						caretPosition = textArea.selectionStart;
					}
				} else { 
					caretPosition = textArea.selectionStart;
					selection = $$.val().substring(caretPosition, textArea.selectionEnd);
				} 
				return selection;
			}

			
			function preview() {
				if (!iFrame) {
					if (options.previewIFrame === true) {
						iFrame = $('<iframe class="markItUpPreviewFrame"></iframe>').insertAfter(footer).show();
						win = iFrame[iFrame.length-1].contentWindow || frame[iFrame.length-1];
					} else {
						win = window.open("", "preview", "resizable=yes, scrollbars=yes");
					}
				} else {
					if (altKey && iFrame) {
						iFrame.remove(); 
						iFrame = false;
						win = false;
					} 
				}
			}

			
			function refreshPeview() {
				var html;
				if (options.previewParserPath !== "") {
					$.ajax({ 
						type: "POST", 
						async: false,
						url: options.previewParserPath, 
						data: options.previewParserVar+"="+escape($$.val()), // thanks Ben for "escape"
						success: function(data) { html = data; },
						error: function() { alert("markItUp! Error: Parser not found."); }
					});
				} else {
					html = '<html>\n<head>\n<meta http-equiv="content-type" content="text/html; charset='+options.previewCharset+'">\n<title></title>\n';
					if (options.previewBaseUrl !== "") { 
						html += '<base href="'+options.previewBaseUrl+'" />\n'; 
					}
					html += '<link href="'+options.previewCssPath+'" rel="stylesheet" type="text/css">\n</head>\n';
					html += '<body id="'+options.previewBodyId+'" class="'+options.previewBodyClassName+'">\n'+$$.val()+'\n</body>\n';
					html += '</html>';
				}

				win.document.open();
				win.document.write(html);
				setTimeout(function() { win.document.close();}, 100);
				
				if (iFrame === false) { win.focus(); }
			}

			
			function keyPressed(e) { 

				shiftKey = e.shiftKey;
				altKey = e.altKey;
				ctrlKey = (!(e.altKey && e.ctrlKey)) ? e.ctrlKey : false; 

				$.extend(hash, { ctrlKey:ctrlKey, shiftKey:shiftKey, altKey:altKey  }); 

				if (e.type === "keydown") {
					if (ctrlKey) {
						var a = $("a[accesskey="+String.fromCharCode(e.keyCode)+"]", header);
						if (a.length !== 0) {
							ctrlKey = false;
							a.parent("li").trigger("click");
							e.preventDefault(); 
							e.stopPropagation(); 
							return false;
						}
					}
					
					if (e.keyCode === 13 || e.keyCode === 10) { 
						if (ctrlKey === true) {  
							ctrlKey = false;
							markup(options.onCtrlEnter);
							return options.onCtrlEnter.keepDefault;
						} else if (shiftKey === true) { 
							shiftKey = false;
							markup(options.onShiftEnter);
							return options.onShiftEnter.keepDefault;
						} else { 
							markup(options.onEnter);
							return options.onEnter.keepDefault;
						}
					}
					if (e.keyCode === 9) { 
						if (caretOffset !== -1) {
							get();
							caretOffset = $$.val().length - caretOffset;
							set(caretOffset, 0);
							caretOffset = -1;
							return false;
						} else {
							markup(options.onTab);
							caretOffset = -1;
							return options.onTab.keepDefault;
						}
					}
				}
			}
			
			init();
		});
	};
	
	$.markItUp = function(settings) {
		var options = { target:false };
		$.extend(options, settings);
		if (options.target) {
			return $(options.target).each(function() {
				$("textarea", this).focus();
				$("textarea", this).trigger("insertion", [options]);
			});
		} else {
			$("textarea").trigger("insertion", [options]);
		}
	};
})(jQuery);