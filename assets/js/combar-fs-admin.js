(function($) {

	$(document).ready(function() {

		// return to last viewed screen
		let lastView = $.cookie('lastView');
		if (undefined === lastView) {
			lastView = 'general';
		}
		let targetBox = '.admin-box.' + lastView;
		let targetTab = '.admin-tab.' + lastView + '-tab';

		$(targetBox).removeClass('hide');
		$(targetTab).addClass('active');

		// return to last view preview by tab
		let preview = $.cookie('fs-preview');
		if (undefined === preview) {
			preview = 'sidebar';
		}
		$('.fs-preview-box').addClass('show-' + preview);
		
		// return preview device view 
		let device = $.cookie('fs-device');
		if (undefined === device) {
			device = 'desk';
		}
		$('.device-switcher span[data-device="' + device + '"]').addClass('act');
		$('.fs-preview-box').addClass('fs-prev-' + device);
		
		
		// return darkmode preview	
		let dark = $.cookie('fs-dark');
		if (undefined === dark) {
			dark = false;
		}
		if (true == dark) {
			$('.fs-preview-box').addClass('fs-prev-dark');
		} else {
			$('.darkmode-switcher span').addClass('act');
		}

		
		// fire color picker
		let colorPickerOptions = {
			change: function(event, ui) {
				setTimeout(function() {
					fsPreviewUpdate();
				}, 10);
			}
		};
		$('form .color-picker').wpColorPicker(colorPickerOptions);

		// fire icon picker
		$('.fa-picker').iconpicker();


		// fire sortable
		$('#sortable.social-options').sortable({
			handle: '.drag-handle .dashicons',
			placeholder: 'ui-state-highlight',
			update: function() {
				fsPreviewUpdate();
			}
		});
		
		$('#sortable.elements-container').sortable({
			placeholder: 'ui-state-highlight',
			handle: '#moveElement',
			update: function() {
				fsPreviewUpdate();
			}
		});

		// validate form
		$('#sf-form').validate({
			errorElement: 'pre'
		});

		// fire Tinymce
		$('.fs-editor').each(function() {
			let editorId = $(this).prop('id');
			wp.editor.initialize(editorId, {
				tinymce: {
				  wpautop: true,
				  toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr | alignleft aligncenter alignright | link unlink | fullscreen',
				  toolbar2: 'formatselect alignjustify forecolor backcolor | pastetext removeformat charmap | outdent indent | undo redo',
				  height : '200'
				},
				mediaButtons: false,
				quicktags: false
			});	
		});
				
		// set up preview
		fsPreviewUpdate();

	});


	// tabs
	$(document).on('click', 'a.admin-tab', function(e) {

		e.preventDefault();

		let target = $(this).attr('href'),
			preview = $(this).data('preview');
		
		target = target.replace('#', '');
		let targetClass = '.admin-box.' + target;

		// update last view input
		let cookie = $.cookie('lastView');
		$.cookie('lastView', target);

		let cookie_preview = $.cookie('fs-preview');
		$.cookie('fs-preview', preview);

		$('a.admin-tab').removeClass('active');
		$(this).addClass('active');

		$('.admin-box').addClass('hide');
		$(targetClass).removeClass('hide');
		
		$('.fs-preview-box').removeClass('show-sidebar').removeClass('show-trigger');
		$('.fs-preview-box').addClass('show-' + preview);
		

	});
	
	// preview device view switcher
	$(document).on('click', '.device-switcher span', function() {
		let device = $(this).data('device');
		let cookie = $.cookie('fs-device');
		
		// button class
		$('.device-switcher span').removeClass('act');
		$(this).addClass('act');
		
		// preview window class
		$('.fs-preview-box').removeClass('fs-prev-desk').removeClass('fs-prev-mob');
		$('.fs-preview-box').addClass('fs-prev-' + device);
		$.cookie('fs-device', device);
	});

	// preview darkmode view switcher
	$(document).on('click', '.darkmode-switcher span', function() {

		let cookie = $.cookie('fs-dark');
		let dark = true;
		if ($(this).hasClass('act')) {
			dark = false;	
		}
		
		$(this).toggleClass('act');
		$('.fs-preview-box').toggleClass('fs-prev-dark');
		$.cookie('fs-dark', dark);
		
	});
	
	// nopages input
	$(document).on('change', '.nopages input', function() {
		let nopage = [];
		$('.nopages input').each( function() {
			if ($(this).is(':checked')) {
				nopage.push($(this).val());
			}	
		});
		$('input[name="combar_fs[adv][nopage]"]').val(nopage.join(','));	
	});

	// media uploader
	$(document).on('click', '.image-option #upload_image_button, .image-option .thumb', function(e) {

		e.preventDefault();
		let mediaUploader;
		let parentOption = $(this).parents('.image-option');

		if (mediaUploader) {
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame = wp.media({}, {
			multiple: false
		});

		mediaUploader.on('select', function() {
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			parentOption.find('input[type="text"]').val(attachment.id);
			parentOption.find('.thumb').attr("src", attachment.url).show(0);
			fsPreviewUpdate();
		});

		mediaUploader.open();
	});

	$(document).on('click', '#remove_image_button', function(e) {
		e.preventDefault();
		let parentOption = $(this).parents('.image-option');
		parentOption.find('input[type="text"]').val('');
		parentOption.find('.thumb').attr('src', '');
		fsPreviewUpdate();
	});

	// update icon picker
	$(document).on('click', '.icon-picker label > i', function() {
		$(this).next('input').focus();
	});

	$(document).on('iconpickerSelected', '.fa-picker', function() {
		let value = $(this).val();
		$(this).prev('i').attr('class', value);
		fsPreviewUpdate();
	});
	
	// wysiwyg editor save
	$(document).on('click', '#saveWYS', function() {
		let editorID = $(this).data('editor');
		tinyMCE.get(editorID).focus();
		tinymce.activeEditor.save();
		fsPreviewUpdate();
	});	

	// close pop message
	$(document).on('click', '.close-msg', function() {
		$(this).parent().removeClass('msg-on');
	});

	// error if form is not valid on submit
	$(document).on('click submit', '.submit-btn', function() {
		if (!$('#sf-form').valid()) {
			sf_pop_message('error', combar_fs.unvalid);
		}
	});

	// update options ajax
	$(document).on('submit', '#sf-form', function(e) {
		e.preventDefault();
		$('.submit-btn').addClass('loading').prop('disabled', 'disabled');
		let str = $(this).serialize();
		$.post('options.php', str)
			.error(function() {
				$('.submit-btn').removeClass('loading').prop('disabled', false);
				sf_pop_message('error', combar_fs.error);
			}).success(function() {
				$('.submit-btn').removeClass('loading').prop('disabled', false);
				sf_pop_message('successes', combar_fs.saved);
				
				// show / hide development mode notice
				if ($('#devModeInput').is(':checked')) {
					$('.dev-mode-notice').addClass('dev-on');
				} else {
					$('.dev-mode-notice').removeClass('dev-on');					
				}	
			});
		return false;

	});

	// restart options to default
	$(document).on('click', '#restartOptions', function(e) {
		e.preventDefault();
		if (confirm(combar_fs.caution + ': ' + combar_fs.alert_reset + '\r\n' + combar_fs.toContinue) == true) {
			let wpnonce = $(this).data('nonce');
			let str = '&action=combar_fs_restart_options&wpnonce=' + wpnonce;

			$.ajax({
				type: "POST",
				url: combar_fs.ajaxurl,
				data: str,
				success: function(data) {
					if (true == data) {
						location.reload(true);
					} else {
						sf_pop_message('error', combar_fs.error);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					sf_pop_message('error', combar_fs.error);
					console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		}
	});

	function sf_pop_message(type = 'successes', content) {

		let unique = 'msg-' + fsGetRandomUnique(4);
		let uniqueClass = '.' + unique;

		let msg = '<div class="sf-msg ' + unique + ' type-' + type + '">';
		msg += '<span class="dashicons dashicons-no-alt close-msg"></span>';
		msg += '<span class="msg-content">' + content + '</span>';
		msg += '</div>';

		$('.sf-msg').removeClass('msg-on');

		$('#wpbody-content > .wrap').append(msg);


		setTimeout(function() {
			$(uniqueClass).addClass('msg-on');
		}, 10);

		setTimeout(function() {
			$(uniqueClass).removeClass('msg-on');

			setTimeout(function() {
				$(uniqueClass).remove();
			}, 500);

		}, 3000);

	}

	function fsGetRandomUnique(length) {
		let randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		let result = '';
		for (var i = 0; i < length; i++) {
			result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
		}
		return result;
	}

	// elements creator
	$(document).ready(function() {
		$('.element-item.close .element-content').hide(0);
	});

	// add elements accordion
	$(document).on('click', '.add-element', function() {
		$('.element-select').fadeToggle(0).focus();
	});


	// elements accordion
	$(document).on('click', '.element-title', function() {
		$(this).parents('.element-item').toggleClass('close');
		$(this).parents('.element-item').find('.element-content').slideToggle(300);
	});

	// show/hide preview note
	$(document).on('click', '.previewBarHead u', function() {
		$(this).parents('.previewBar').toggleClass('show');
		$(this).parents('.previewBar').find('ol').slideToggle();
	});


	// design options accordion
	$(document).on('click', '.design-options-toggle', function() {
		$(this).toggleClass('open');
		$(this).next().slideToggle(300);
	});

	// delete element
	$(document).on('click', '#deleteElement', function() {
		if (confirm(combar_fs.confirmDelete) == true) {
			$(this).parents('.element-item').remove();
			fsPreviewUpdate();
		}
	});

	// add element ajax
	$(document).on('click', '.element-btn', function(e) {
		e.preventDefault();

		let wpnonce = $('.add-element').data('nonce');
		type = $(this).data('type');

		if (!type) {
			sf_pop_message('error', combar_fs.error);
		} else {
			let str = '&action=combar_fs_element_fields_ajax&wpnonce=' + wpnonce + '&type=' + type;

			$.ajax({
				type: "POST",
				url: combar_fs.ajaxurl,
				data: str,
				success: function(data) {
					if (data) {
						$('.elements-container').append(data);

						let colorPickerOptions = {
							change: function(event, ui) {
								setTimeout(function() {
									fsPreviewUpdate();
								}, 10);
							}
						};
						
						// fire color picker for new item
						$('.element-item:last-child .color-picker').wpColorPicker(colorPickerOptions);
						
						// fire icon picker for new item
						$('.element-item:last-child .fa-picker').iconpicker();	
						
						// fire WYSIWYG for new item
						let wysiwygId = $('.element-item:last-child .fs-editor').prop('id');
						wp.editor.initialize(wysiwygId, {
							tinymce: {
							  wpautop: true,
							  toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr | alignleft aligncenter alignright | link unlink | fullscreen',
							  toolbar2: 'formatselect alignjustify forecolor backcolor | pastetext removeformat charmap | outdent indent | undo redo',
							  height : '200'
							},
							mediaButtons: false,
							quicktags: false
						});	
			
						// hide items menu				
						$('.element-select').hide(0);
						
						// scroll to new item
						$("html, body").animate({
							scrollTop: $(document).height()
						}, 1000);
						
						
						
						fsPreviewUpdate();

					} else {
						sf_pop_message('error', combar_fs.error);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					sf_pop_message('error', combar_fs.error);
					console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		}

	});

	/* PREWVIEW 
	 * set update preview interval
	 * in order not to overload the server with ajax requests
	 * requests will be sent only after typing is complete
	 */

	let typingTimeTimout = 1000;
	let typingTimer = setTimeout(function() {}, typingTimeTimout);

	//on keyup, start the countdown
	$(document).on('keyup', 'input', function() {
		clearTimeout(typingTimer);
		fsPreviewUpdate();
		typingTimer = setTimeout(function() {
			fsPreviewUpdate()
		}, typingTimeTimout);
	});

	$(document).on('change paste', 'input, select', function() {
		fsPreviewUpdate();
	});

	//on keydown, clear the countdown 
	$(document).on('keydown', 'input', function() {
		clearTimeout(typingTimer);
	});
	
	$(document).on('click', '.fs-preview-box a', function(e) {
		e.preventDefault();
	});	


	function fsPreviewUpdate() {
		if ($('#sf-form').valid()) {
			let nonce = $('#previewnonce').data('nonce');
			let str = $('#sf-form').serialize();
			str += '&action=combar_fs_preview_ajax';
			str += '&prev_nonce=' + nonce;
			$.ajax({
				type: "POST",
				url: combar_fs.ajaxurl,
				data: str,
				success: function(data) {
					$('.fs-preview-box').empty();
					$('.fs-preview-box').html(data);

					// fix outside close
					let max;
					if ($('.fs-close-outside').length) {
						
						let closeGap = $('input[name="combar_fs[close][gap]"]').val();
						if (!closeGap) {
							closeGap = 5;
						}
	
						max = $('.fs-close').outerWidth() + closeGap;
						
						$('.combar-fs-container').css('max-width', 'calc(100% - ' + max + 'px)');
						
						let side = $('.combar-fs-container').outerWidth();
						
						if ('left' == $('.sideSwitcher:checked').val()) {
							$('.fs-close-outside').css('width', 'calc(100% - ' + side + 'px)').css('left', side + 'px').css('right', 'auto');
						} else {
							$('.fs-close-outside').css('width', 'calc(100% - ' + side + 'px)').css('right', side + 'px').css('left', 'auto');
						}
						
					} else {
						$('.combar-fs-container').css('max-width', 'calc(100% - 30px)');
					}
					
					// toggle body preview side class
					let sideClass = $('.sideSwitcher:checked').val();
					$('body').removeClass('fs-side-left').removeClass('fs-side-right');
					$('body').addClass('fs-side-' + sideClass);

				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
		}
	}

})(jQuery);