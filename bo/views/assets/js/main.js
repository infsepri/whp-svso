// global variables
var isIE8 = false;
var isIE9 = false;
var $windowWidth;
var $windowHeight;
var $pageArea;
var isMobile = false;
// Debounce Function
(function($, sr) {
	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function(func, threshold, execAsap) {
		var timeout;
		return function debounced() {
			var obj = this, args = arguments;

			function delayed() {
				if(!execAsap)
					func.apply(obj, args);
				timeout = null;
			};

			if(timeout)
				clearTimeout(timeout);
			else if(execAsap)
				func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 100);
		};
	};
	// smartresize
	jQuery.fn[sr] = function(fn) {
		return fn ? this.on('resize', debounce(fn)) : this.trigger(sr);
	};

})(jQuery, 'clipresize');

//Main Function
var Main = function() {
	//function to detect explorer browser and its version
	var runInit = function() {
		if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
			var ieversion = new Number(RegExp.$1);
			if(ieversion == 8) {
				isIE8 = true;
			} else if(ieversion == 9) {
				isIE9 = true;
			}
		}
		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			isMobile = true;
		};
	};
	//function to adjust the template elements based on the window size
	var runElementsPosition = function() {
		$windowWidth = $(window).width();
		$windowHeight = $(window).height();
		$pageArea = $windowHeight - $('body > .navbar').outerHeight() - $('body > .footer').outerHeight()-65;
		if(!isMobile) {
			$('.sidebar-search input').removeAttr('style').removeClass('open');
		}
		runContainerHeight();

	};
	//function to adapt the Main Content height to the Main Navigation height
	var runContainerHeight = function() {
		mainContainer = $('.main-content > .container').not(".fixHeight");
		mainNavigation = $('.main-navigation');
	
		if($pageArea < 760) {
			$pageArea = 760;
		}
		
		//mainContainer.css('padding-top', $('body > .navbar').outerHeight());
		if(mainContainer.outerHeight() <= mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
			mainContainer.css('min-height', mainNavigation.outerHeight());
		} else {
			mainContainer.css('min-height', $pageArea);
			
		};
		
		if($windowWidth < 768) {
			mainNavigation.css('min-height', $windowHeight - $('body > .navbar').height()+10);
			if(mainContainer.outerHeight() <= mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
				mainContainer.css('min-height', mainNavigation.outerHeight());
			} else {
				mainContainer.css('min-height', $pageArea);
			};
			
		}
		$("#page-sidebar .sidebar-wrapper").css('height', $windowHeight - $('body > .navbar').outerHeight()).scrollTop(0).perfectScrollbar('update');

	};
	
	
	//function to open quick sidebar
	var runQuickSideBar = function() {
		$(".sb-toggle").on("click", function(e) {
			if($(this).hasClass("open")) {
				$(this).not(".sidebar-toggler ").find(".fa-indent").removeClass("fa-indent").addClass("fa-outdent");
				$(".sb-toggle").removeClass("open")
				$("#page-sidebar").css({
					right: -$("#page-sidebar").outerWidth()
				});
			} else {
				$(this).not(".sidebar-toggler ").find(".fa-outdent").removeClass("fa-outdent").addClass("fa-indent");
				$(".sb-toggle").addClass("open")
				$("#page-sidebar").css({
					right: 0
				});
			}

			e.preventDefault();
		});
		$("#page-sidebar .media a").on("click", function(e) {
			$(this).closest(".tab-pane").css({
				right: $("#page-sidebar").outerWidth()
			});
			e.preventDefault();
		});
		$("#page-sidebar .sidebar-back").on("click", function(e) {
			$(this).closest(".tab-pane").css({
				right: 0
			});
			e.preventDefault();
		});
		$('#page-sidebar .sidebar-wrapper').perfectScrollbar({
			wheelSpeed: 50,
			minScrollbarLength: 20,
			suppressScrollX: true
		});
		$('#sidebar-tab a').on('shown.bs.tab', function (e) {
		 
		 $("#page-sidebar .sidebar-wrapper").perfectScrollbar('update');
		});
	};
	
	
	
	var runPanelScroll = function() {
		if($(".panel-scroll").length) {
			$('.panel-scroll').perfectScrollbar({
				wheelSpeed: 50,
				minScrollbarLength: 20,
				suppressScrollX: true
			});
		}
	};
	
	//function to reduce the size of the Main Menu
	var runNavigationToggler = function() {
		$('.navigation-toggler').on('click', function() {
			if(!$('body').hasClass('navigation-small')) {
				$('body').addClass('navigation-small');
			} else {
				$('body').removeClass('navigation-small');
			};
		});
	};
	
	//function to activate the panel tools
	var runModuleTools = function() {
		$('.panel-tools .panel-expand').on('click', function(e) {
			$('.panel-tools a').not(this).hide();
			$('body').append('<div class="full-white-backdrop"></div>');
			$('.main-container').removeAttr('style');
			backdrop = $('.full-white-backdrop');
			wbox = $(this).parents('.panel');
			wbox.removeAttr('style');
			if(wbox.hasClass('panel-full-screen')) {
				backdrop.fadeIn(200, function() {
					$('.panel-tools a').show();
					wbox.removeClass('panel-full-screen');
					backdrop.fadeOut(200, function() {
						backdrop.remove();
					});
				});
			} else {
				$('body').append('<div class="full-white-backdrop"></div>');
				backdrop.fadeIn(200, function() {
					$('.main-container').css({
						'max-height': $(window).outerHeight() - $('header').outerHeight() - $('.footer').outerHeight() - 100,
						'overflow': 'hidden'
					});
					backdrop.fadeOut(200);
					backdrop.remove();
					wbox.addClass('panel-full-screen').css({
						'max-height': $(window).height(),
						'overflow': 'auto'
					});
				});
			}
		});
		$('.panel-tools .panel-close').on('click', function(e) {
			$(this).parents(".panel").remove();
			e.preventDefault();
		});
		$('.panel-tools .panel-refresh').on('click', function(e) {
			var el = $(this).parents(".panel");
			el.block({
				overlayCSS: {
					backgroundColor: '#fff'
				},
				message: '<img src="assets/images/loading.gif" /> Just a moment...',
				css: {
					border: 'none',
					color: '#333',
					background: 'none'
				}
			});
			window.setTimeout(function() {
				el.unblock();
			}, 1000);
			e.preventDefault();
		});
		$('.panel-tools .panel-collapse').on('click', function(e) {
			e.preventDefault();
			var el = jQuery(this).parent().closest(".panel").children(".panel-body");
			if($(this).hasClass("collapses")) {
				$(this).addClass("expand").removeClass("collapses");
				el.slideUp(200);
			} else {
				$(this).addClass("collapses").removeClass("expand");
				el.slideDown(200);
			}
		});
	};
	//function to activate the 3rd and 4th level menus
	var runNavigationMenu = function() {
		$('.main-navigation-menu li.active').addClass('open');
		$('.main-navigation-menu > li a').on('click', function() {
			if($(this).parent().children('ul').hasClass('sub-menu') && ((!$('body').hasClass('navigation-small') || $windowWidth < 767) || !$(this).parent().parent().hasClass('main-navigation-menu'))) {
				if(!$(this).parent().hasClass('open')) {
					$(this).parent().addClass('open');
					$(this).parent().parent().children('li.open').not($(this).parent()).not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200);
					$(this).parent().children('ul').slideDown(200, function() {
						runContainerHeight();
					});
				} else {
					if(!$(this).parent().hasClass('active')) {
						$(this).parent().parent().children('li.open').not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200, function() {
							runContainerHeight();
						});
					} else {
						$(this).parent().parent().children('li.open').removeClass('open').children('ul').slideUp(200, function() {
							runContainerHeight();
						});
					}
				}
			}
		});
	};
	//function to activate the Go-Top button
	var runGoTop = function() {
		$('.go-top').on('click', function(e) {
			$("html, body").animate({
				scrollTop: 0
			}, "slow");
			e.preventDefault();
		});
	};
	//function to avoid closing the dropdown on click
	var runDropdownEnduring = function() {
		if($('.dropdown-menu.dropdown-enduring').length) {
			$('.dropdown-menu.dropdown-enduring').click(function(event) {
				event.stopPropagation();
			});
		}
	};
	//function to return the querystring parameter with a given name.
	var getParameterByName = function(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	};

	//Window Resize Function
	var runWIndowResize = function(func, threshold, execAsap) {
		//wait until the user is done resizing the window, then execute
		$(window).clipresize(function() {
			runElementsPosition();
		});
	};
	
	
	//StackableModals 
	var runModalStack = function() {
		$('.modal:not(.stackModal)').each(function () {
			$(this).on('show.bs.modal', function (event) {
				var openModals = $(".modal.in").length;
				openModals = openModals+2;
				
				$(this).css('z-index', 1040 + (10 * openModals));
			});
			$(this).on('shown.bs.modal', function (event) {
				var openModals = $(".modal.in").length;
				openModals++;
				
				$('.modal-backdrop:not(:first)').hide();
				$('.modal-backdrop:first').css('z-index', 1039 + (10 * openModals));
			});
			$(this).on('hidden.bs.modal', function(event) {
				var openModals = $(".modal.in").length;
				if(openModals > 0) {
					$("body").addClass('modal-open');
				}
				
				$('.modal-backdrop:not(:first)').hide();
				$('.modal-backdrop:first').css('z-index', 1039 + (10 * openModals));
			});
			
			$(this).addClass("stackModal");
		});
		
	};
	
	//Tooltips
	var runTooltips = function() {
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	};
	
	return {
		//main function to initiate template pages
		init: function() {
			runWIndowResize();
			runInit();
			runElementsPosition();
			runNavigationToggler();
			runNavigationMenu();
			runGoTop();
			runModuleTools();
			runDropdownEnduring();
			runPanelScroll();
			runQuickSideBar();
			runModalStack();
			runTooltips();
		},
		modal: function () {
			runModalStack();
		}
	};
}(); 