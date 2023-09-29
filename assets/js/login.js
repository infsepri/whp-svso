// global variables
var isIE8 = false;
var isIE9 = false;
var $windowWidth;
var $windowHeight;
var $pageArea;
var isMobile = false;

(function($, sr) {
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
	jQuery.fn[sr] = function(fn) {
		return fn ? this.on('resize', debounce(fn)) : this.trigger(sr);
	};

})(jQuery, 'clipresize');

var Login = function () {
    var runLoginButtons = function () {
		var el = $('.box-login');
		if (getParameterByName('box').length) {
			switch(getParameterByName('box')) {
				case "forgot" :
					el = $('.box-forgot');
					break;
				case "forgotUser" :
					el = $('.box-forgotUser');
					break;	
				default :
					el = $('.box-login');
					break;
			}
		}
		el.show();
    };
	
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
	
	var runElementsPosition = function() {
		$windowWidth = $(window).width();
		$windowHeight = $(window).height();
		$pageArea = $windowHeight - $('body > .navbar').outerHeight() - $('body > .footer').outerHeight();
		if(!isMobile) {
			$('.sidebar-search input').removeAttr('style').removeClass('open');
		}
		runContainerHeight();
	};
	
	var runContainerHeight = function() {
		mainContainer = $('.main-content > .container').not(".fixHeight");
		mainNavigation = $('.main-navigation');
	
		if($pageArea < 760) {
			$pageArea = 760;
		}
		if(mainContainer.outerHeight() <= mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
			mainContainer.css('min-height', mainNavigation.outerHeight()+$('body > .footer').outerHeight());
		} else {
			mainContainer.css('min-height', $pageArea+$('body > .footer').outerHeight());
		};
		
		if($windowWidth < 768) {
			mainNavigation.css('min-height', $windowHeight - $('body > .navbar').height()+10);
			if(mainContainer.outerHeight() <= mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
				mainContainer.css('min-height', mainNavigation.outerHeight());
			} else {
				mainContainer.css('min-height', $pageArea);
			};
		}
	};
	
	
	var runWIndowResize = function(func, threshold, execAsap) {
		$(window).clipresize(function() {
			runElementsPosition();
		});
	};
	
	
	var getParameterByName = function(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	};
    
    
    return {
        init: function () {
            runLoginButtons();
			runWIndowResize();
			runInit();
			runElementsPosition();
        }
    };
}();


jQuery(document).ready(function() {
	Login.init();
});
