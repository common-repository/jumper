(function($) {

	// sidebar trigger
	$(document).ready(function() {

		//outside close fix if width is bigger then screen
		combarFsFix();

		let rtl = false;
		if ('rtl' == $('html').attr('dir')) {
			rtl = true;
		}
		$('.combar-fs-scrollbox').scrollbar({
			"isRtl": rtl
		})
	});

	$(window).on('resize', function() {
		combarFsFix();
	});	
	
	$(document).on('click', '#fs-trigger', function(e) {
		e.preventDefault();
		openCombarSf();
	});


	if (('' != combar_fs.selector && ' ' != combar_fs.selector) && $(combar_fs.selector).length) {
		$(document).on('click', combar_fs.selector, function() {
			openCombarSf();
		});
	}

	$(document).on('click', '.combar-fs-overlay, .fs-close', function() {
		closeCombarFs();
	});


	// esc click support
	$(document).on('keydown', function(e) {
		if ('on' == combar_fs.esc && 27 == e.which) {
			closeCombarFs();
		}
	});

	// hash change support
	window.addEventListener("hashchange", combarFsHashChange);	
	function combarFsHashChange(e) {
		let oldURL = e.oldURL.split('#')[1],
			newURL = e.newURL.split('#')[1];

		// close on back button
		if (oldURL == 'fs-open') {
			closeCombarFs();
		}
	}
	

	// open sidebar function
	function openCombarSf() {
		$('body').addClass('fs-open');

		if ('on' == combar_fs.scroll_dis) {
			$('body').addClass('fs-disable-scroll');
		}

		if ('on' == combar_fs.hash) {
			window.location.hash = 'fs-open';
		}
	}

	// close sidebar function
	function closeCombarFs() {

		$('body').removeClass('fs-open');

		setTimeout(function() {
			$('body').removeClass('fs-disable-scroll');
		}, combar_fs.duration);

		// remove hash
		history.replaceState(null, null, ' ');

	}
	
	function combarFsFix() {
		
		let windowWidth = $(window).width();

		if ($('.fs-close-outside').length) {
			
			let width = parseInt(combar_fs.width),
				containerWidth = $('.combar-fs-container').outerWidth(),
				close_width =  parseInt(combar_fs.close_width),
				close_gap =  parseInt(combar_fs.close_gap);
			
			if (width + close_width + close_gap >= windowWidth) {
				let outsideGap = windowWidth - containerWidth;

				if ('left' == combar_fs.side) {
					$('.fs-close-outside').css('left', outsideGap + 'px');
				} else if ('right' == combar_fs.side) {
					$('.fs-close-outside').css('right', outsideGap + 'px');
				}

			} else {

				if ('left' == combar_fs.side) {
					$('.fs-close-outside').css('left', containerWidth + 'px');
				} else if ('right' == combar_fs.side) {
					$('.fs-close-outside').css('right', containerWidth + 'px');
				}
			}
		}
	}

})(jQuery);