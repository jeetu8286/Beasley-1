(function(jQuery, window, undefined) {

	var $mobileMenu = jQuery(document.querySelectorAll('ul.js-mobile-sub-menus'));

	function init() {

		$mobileMenu.on('click.greaterMedia.Menus', 'a.show-subnavigation', openSubMenu);

		$mobileMenu.on('click.greaterMedia.Menus', 'a.mobile-menu-submenu-back-link', closeSubMenu);

	}

	function closeSubMenu(event) {
		event.preventDefault();
		jQuery(this).parents('.sub-menu').removeClass('is-visible');
	}

	function openSubMenu(event) {
		event.preventDefault();

		// collapse any other open menus before opening ours.
		$mobileMenu.find('.is-visible').removeClass('is-visible');
		jQuery(this).siblings('.sub-menu').addClass('is-visible');
	}

	init();

})(jQuery, window);
(function($) {

	var ProfileMenuApp = function() {

	};

	ProfileMenuApp.prototype = {

		run: function() {
			var $body = $('body');
			var $largeLink = $('.header__account--large');
			if ($body.hasClass('gmr-user')) {
				$largeLink.toggleClass('logged-in');
			}

			var $container = $('.header__account--container');
			$container.append(this.getMenu());

			var $avatar = $('.header__account--btn');
			$avatar.attr('href', this.getAvatarLink());

			var thumbnailURL = this.getThumbnailURL();
			if (thumbnailURL) {
				var $img = $('<img />', { src: thumbnailURL });
				$avatar.html($img);
			}
		},

		getAvatarLink: function() {
			var endpoint = is_gigya_user_logged_in() ? 'account' : 'login';
			return gigya_profile_path(endpoint);
		},

		getThumbnailURL: function() {
			return get_gigya_user_field('thumbnailURL');
		},

		getMenu: function() {
			var menu  = this.getMenuLabels();
			var n     = menu.length;
			var $menu = $('<ul class="header__account--links sub-menu"></ul>');
			var $li, $a, item;

			for ( var i = 0; i < n; i++ ) {
				item = menu[i];
				$li = $('<li></li>');

				$a = $('<a></a>', { href: gigya_profile_path(item.endpoint) });
				$a.text(item.label);
				$li.append($a);

				$menu.append($li);
			}

			return $menu;
		},

		getMenuLabels: function() {
			var menu;

			if (is_gigya_user_logged_in()) {
				menu = [
					{ label: 'Edit Account' , endpoint: 'account' } ,
					{ label: 'Logout'       , endpoint: 'logout' }
				];
			} else {
				menu = [
					{ label: 'Login/Register', endpoint: 'login' }
				];
			}

			return menu;
		}

	};

	$(document).ready(function() {
		var app = new ProfileMenuApp();
		app.run();
	});

}(jQuery));

(function ($) {
	// we don't need to use pjax:end event here
	$(document).ready(function() {
		var $onair = $('#on-air'),
			schedule = [],
			fallback = '',
			current_show = {},
			track_schedule, update_onair;

		if ($onair.length == 0) {
			return;
		}

		update_onair = function(title, show) {
			$onair.find('.on-air__title').text(title);
			$onair.find('.on-air__show').text(show);
		};

		track_schedule = function() {
			var now = new Date(),
				next = new Date(now.getTime() + 10 * 60 * 1000), // 10 minutes later
				found = false,
				starts, ends;

			for (var i = 0; i < schedule.length; i++) {
				starts = new Date(schedule[i].starts * 1000);
				ends = new Date(schedule[i].ends * 1000);
				
				if (starts <= now && now <= ends) {
					current_show = schedule[i];
					update_onair('On Air:', schedule[i].title);
					found = true;
				}

				if (starts <= next && next <= ends && schedule[i].title != current_show.title) {
					update_onair('Up Next:', schedule[i].title);
					found = true;
				}
			}

			if (!found) {
				update_onair('', fallback);
			}
		};
		
		$.get($onair.data('endpoint'), function(response) {
			if (response.success && response.data) {
				fallback = response.data.tagline || '';
				if ($.isArray(schedule)) {
					schedule = response.data.schedule;
				}
				
				track_schedule();
				setInterval(track_schedule, 1000);
			}
		});
	});
})(jQuery);

(function ($) {
	var $window = $(window);

	var __ready = function() {
		var $days = $('.shows__schedule--day'),
			header_bottom = $('#wpadminbar').outerHeight(),
			on_scroll;

		on_scroll = function() {
			var scroll_top = $window.scrollTop();
			
			$days.each(function() {
				var $day = $(this),
					$weekday = $day.find('.shows__schedule--dayofweek'),
					day_top = $day.offset().top,
					day_left = $day.offset().left,
					day_bottom = $day.height() + $day.offset().top,
					own_height = $weekday.height(),
					top;

				if (scroll_top + header_bottom >= day_top) {
					$day.addClass('fixed');

					top = scroll_top + header_bottom + own_height >= day_bottom
						? day_bottom - scroll_top - own_height
						: header_bottom;

					$weekday.width($day.width()).css({
						top: top + 'px',
						left: day_left + 'px'
					});
				} else {
					$day.removeClass('fixed');
					$weekday.width('auto').css({
						top: '0px',
						left: '0px'
					});
				}
			});
		};

		$window.resize(on_scroll);
		$window.scroll(on_scroll);

		on_scroll();
	};

	$(document).bind('pjax:end', __ready).ready(__ready);
})(jQuery);
(function($) {

	var findElementByClassPrefix = function($node, prefix) {
		var classList = $node.attr('class').split(' ');
		var n         = classList.length;
		var className;

		for (var i = 0; i < n; i++) {
			className = classList[i];
			if (className.indexOf(prefix) === 0) {
				return className;
			}
		}

		return null;
	};

	var ArticleFinder = function() {

	};

	ArticleFinder.prototype = {

		find: function() {
			var selector = this.getSelector();
			var $article = $(selector);

			if ( $article.length === 1 ) {
				return this.getArticleFromNode($article);
			} else {
				return null;
			}
		},

		getSelector: function() {
			return '.main .content .article';
		},

		getArticleFromNode: function($article) {
			var article = {
				id       : this.getArticleID($article),
				postType : this.getArticlePostType($article)
			};

			if (article.id !== null && article.postType !== null) {
				return article;
			} else {
				return null;
			}
		},

		getArticleID: function($article) {
			var id = $article.attr('id');
			var startsWithPost = id.indexOf('post-') === 0;
			var articleID;

			if (startsWithPost) {
				return id.substring(5);
			} else {
				return null;
			}
		},

		getArticlePostType: function($article) {
			var postTypeClass = findElementByClassPrefix($article, 'type-');
			if (postTypeClass !== null) {
				return postTypeClass.substring(5);
			} else {
				return null;
			}
		}

	};

	var ShareLogger  = function() {
		var self     = this;
		var selector = this.getShareSelector();
		var logger   = function(event) { return self.didShareClick(event); };

		$(selector).click(logger);
	};

	ShareLogger.prototype = {

		share: function(action) {
			save_gigya_action(action);
		},

		didShareClick: function(event) {
			var selector = this.getShareSelector();
			var article  = this.getCurrentArticle();
			var $link    = $(event.target);

			if (article !== null) {
				var params = {
					network : this.getShareNetwork($link),
					url     : this.getShareUrl()
				};

				var action  = this.getShareAction(article, params);
				this.share(action);
			}

			return true;
		},

		getShareSelector: function() {
			return 'a.social__link';
		},

		getCurrentArticle: function() {
			var finder = new ArticleFinder();
			return finder.find();
		},

		getShareNetwork: function($link) {
			var iconClass = findElementByClassPrefix($link, 'icon-');

			if (iconClass !== null) {
				return iconClass.substring(5);
			} else {
				return null;
			}
		},

		getShareUrl: function() {
			return [location.protocol, '//', location.host, location.pathname].join('');
		},

		getShareAction: function(article, params) {
			var action = {
				actionType: 'action:social_share',
				actionID: article.id,
				actionData: [
					{ name: 'network', value: params.network },
					{ name: 'url', value: params.url }
				]
			};

			return action;
		}

	};

	$(document).ready(function() {
		var shareLogger = new ShareLogger();
	});

	/* exports */
	window.ArticleFinder = ArticleFinder;

}(jQuery));

(function($, location) {
	var $document = $(document),
		classes = {},
		last_url = null,
		current_url = location.href,
		normalize_url;

	normalize_url = function(url) {
		return url.replace(/[\?\#].*$/g, '');
	};

	$document.bind('pjax:popstate', function() {
		last_url = normalize_url(current_url);
	});

	$document.bind('pjax:beforeSend', function() {
		last_url = normalize_url(location.href);
	});

	$document.bind('pjax:end', function(e, xhr, options) {
		var $body = $('body'),
			body_classes = false,
			pattern = new RegExp('\<body.*?class=\"(.*?)\"', 'im');

		classes[last_url] = $body.attr('class');

		if (xhr) {
			body_classes = pattern.exec(xhr.responseText);
			if (body_classes && body_classes.length >= 2) {
				$body.attr('class', body_classes[1]);
			}
		} else {
			$body.attr('class', classes[normalize_url(options.url)]);
		}

		current_url = location.href;
	});

	/**
	 * Add "is-busy" class to the body when a Pjax request starts.
	 */
	$document.bind( 'pjax:start', function () {
		$( 'body').addClass( 'is-busy' );
	} );

	/**
	 * Remove the "is-busy" class from the body when a Pjax request ends.
	 */
	$document.bind( 'pjax:end', function () {
		$( 'body').removeClass( 'is-busy' );
	} );
})(jQuery, location);
(function () {

	/**
	 * global variables
	 *
	 * @type {HTMLElement}
	 */
	var $ = jQuery;

	var body = document.querySelector('body'),
		html = document.querySelector('html'),
		mobileNavButton = document.querySelector('.mobile-nav__toggle'),
		pageWrap = document.getElementById('page-wrap'),
		header = document.getElementById('header'),
		livePlayer = document.getElementById('live-player__sidebar'),
		livePlayerStream = document.querySelector('.live-player__stream'),
		livePlayerStreamSelect = document.querySelector('.live-player__stream--current'),
		livePlayerCurrentName = livePlayerStreamSelect.querySelector('.live-player__stream--current-name'),
		livePlayerStreams = livePlayerStreamSelect.querySelectorAll('.live-player__stream--item'),
		wpAdminHeight = 32,
		onAir = document.getElementById( 'on-air' ),
		upNext = document.getElementById( 'up-next'),
		nowPlaying = document.getElementById( 'nowPlaying' ),
		liveLinks = document.getElementById( 'live-links' ),
		liveLink = document.querySelector( '.live-link__title'),
		liveLinksWidget = document.querySelector( '.widget--live-player' ),
		liveLinksWidgetTitle = document.querySelector('.widget--live-player__title'),
		liveLinksWidgetContent = liveLinksWidget.innerHTML,
		liveStream = document.getElementById( 'live-player' ),
		windowWidth = this.innerWidth || this.document.documentElement.clientWidth || this.document.body.clientWidth || 0,
		scrollObject = {},
		collapseToggle = document.querySelector('*[data-toggle="collapse"]'),
		breakingNewsBanner = document.getElementById('breaking-news-banner'),
		$overlay = $('.overlay-mask'),
		livePlayerMore = document.getElementById('live-player--more');

	/**
	 * function to dynamically calculate the offsetHeight of an element
	 *
	 * @param elem
	 * @returns {number}
	 */
	function elemHeight(elem) {
		if (elem != null && elem === header && breakingNewsBanner != null) {
			return elem.offsetHeight + breakingNewsBanner.offsetHeight;
		} else {
			return elem.offsetHeight;
		}
	}

	function elemTopOffset(elem) {
		if (elem != null) {
			return elem.offsetTop;
		}
	}

	function elemHeightOffset(elem) {
		return elemHeight(elem) - elemTopOffset(elem);
	}

	function windowHeight(elem) {
		return Math.max(document.documentElement.clientHeight, elem.innerHeight || 0);
	}

	function elementInViewport(elem) {
		var top = elem.offsetTop;
		var left = elem.offsetLeft;
		var width = elem.offsetWidth;
		var height = elem.offsetHeight;

		while (elem.offsetParent) {
			elem = elem.offsetParent;
			top += elem.offsetTop;
			left += elem.offsetLeft;
		}

		return (
		top < (window.pageYOffset + window.innerHeight) &&
		left < (window.pageXOffset + window.innerWidth) &&
		(top + height) > window.pageYOffset &&
		(left + width) > window.pageXOffset
		);
	}

	/**
	 * global variables for event types to use in conjunction with `addEventHandler` function
	 * @type {string}
	 */
	var elemClick = 'click',
		elemLoad = 'load',
		elemScroll = 'scroll',
		elemResize = 'resize';

	/**
	 * function to detect if the current browser can use `addEventListener`, if not, use `attachEvent`
	 * this is a specific fix for IE8
	 *
	 * @param elem
	 * @param eventType
	 * @param handler
	 */
	function addEventHandler(elem, eventType, handler) {
		if (elem.addEventListener)
			elem.addEventListener(eventType, handler, false);
		else if (elem.attachEvent)
			elem.attachEvent('on' + eventType, handler);
	}

	/**
	 * function for the initial state of the live player and scroll position one
	 */
	function lpPosBase() {
		if (body.classList.contains('logged-in')) {
			livePlayer.style.top = wpAdminHeight + elemHeight(header) + 'px';
			livePlayer.style.height = windowHeight(window) - wpAdminHeight - elemHeight(header) + 'px';
			liveLinks.style.height = windowHeight(window) - wpAdminHeight - elemHeight(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - wpAdminHeight - elemHeight(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		} else {
			livePlayer.style.top = elemHeight(header) + 'px';
			livePlayer.style.height = windowHeight(window) - elemHeight(header) + 'px';
			liveLinks.style.height = windowHeight(window) - elemHeight(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - elemHeight(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		}
		livePlayer.classList.remove('live-player--fixed');
		livePlayer.classList.add('live-player--init');
	}

	/**
	 * function for the live player when a user starts scrolling and the header is not in view
	 */
	function lpPosScrollInit() {
		if (body.classList.contains('logged-in')) {
			livePlayer.style.top = wpAdminHeight + elemHeight(header) + 'px';
			livePlayer.style.height = windowHeight(window) - wpAdminHeight + 'px';
			liveLinks.style.height = windowHeight(window) - elemTopOffset(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - elemTopOffset(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		} else {
			livePlayer.style.top = elemHeight(header) + 'px';
			livePlayer.style.height = windowHeight(window) - wpAdminHeight - elemHeight(header) + 'px';
			liveLinks.style.height = windowHeight(window) - elemHeightOffset(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - elemHeightOffset(header) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		}
		livePlayer.classList.remove('live-player--fixed');
		livePlayer.classList.add('live-player--init');
	}

	/**
	 * function for the live player when the header is no longer in view
	 */
	function lpPosNoHeader() {
		if (body.classList.contains('logged-in')) {
			livePlayer.style.top = wpAdminHeight + 'px';
			livePlayer.style.height = windowHeight(window) - wpAdminHeight + 'px';
			liveLinks.style.height = windowHeight(window) - wpAdminHeight - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - wpAdminHeight - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		} else {
			livePlayer.style.top = '0px';
			livePlayer.style.height = windowHeight(window) + 'px';
			liveLinks.style.height = windowHeight(window) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) + 'px';
			liveLinksWidget.style.maxHeight = windowHeight(window) - elemHeight(livePlayerStreamSelect) - elemHeight(liveStream) - elemHeight(liveLinksWidgetTitle) + 'px';
		}
		livePlayer.classList.remove('live-player--init');
		livePlayer.classList.add('live-player--fixed');
	}

	/**
	 * default height for the live player
	 */
	function lpPosDefault() {
		livePlayer.style.height = '100%';
	}

	/**
     * Toggles a class to the Live Play Stream Select box when the box is clicked
     */
    function toggleStreamSelect() {
        livePlayerStreamSelect.classList.toggle( 'open' );
    }
    addEventHandler(livePlayerStreamSelect,elemClick,toggleStreamSelect);

    /**
     * Selects a Live Player Stream
     */
    function selectStream() {
        var selected_stream = this.querySelector( '.live-player__stream--name' ).textContent;

        livePlayerCurrentName.textContent = selected_stream;
        document.dispatchEvent( new CustomEvent( 'live-player-stream-changed', { 'detail': selected_stream } ) );
    }

    for ( var i = 0; i < livePlayerStreams.length; i++ ) {
        addEventHandler(livePlayerStreams[i],elemClick,selectStream);
    }
    /**
     * from Js Window resize script is not neccessary on popupPlayer window
     */
    if( document.getElementById( 'popup-player-livestream' ) ){
        return;
    }

	/**
	 * detects various positions of the screen on scroll to deliver states of the live player
	 *
	 * y scroll position === `0`: the live player will be absolute positioned with a top location value based
	 * on the height of the header and the height of the WP Admin bar (if logged in); the height will be adjusted
	 * based on the window height - WP Admin Bar height (if logged in) - header height.
	 * y scroll position >= `1` and <= the header height: the live player height will be 100% and will still be
	 * positioned absolute as y scroll position === `0` was.
	 * y scroll position >= the header height: the live player height will be based on the height of the window - WP
	 * Admin bar height (if logged in); the live player will be fixed position at `0` or the height of the WP Admin bar
	 * if logged in.
	 * all other states will cause the live player to have a height of 100%;.
	 */
	function getScrollPosition() {
		if (window.innerWidth >= 768) {
			scrollObject = {
				x: window.pageXOffset,
				y: window.pageYOffset
			};

			if (scrollObject.y == 0) {
				lpPosBase();
			} else if (scrollObject.y >= 1 && elementInViewport(header)) {
				lpPosScrollInit();
			} else if (!elementInViewport(header)) {
				lpPosNoHeader();
			} else {
				lpPosDefault();
			}
		}
	}

	/**
	 * adds some styles to the live player that would be called at mobile breakpoints. This is added specifically to
	 * deal with a window being resized.
	 */
	function livePlayerMobileReset() {
		if (livePlayer != null) {
			if (livePlayer.classList.contains('live-player--init')) {
				livePlayer.classList.remove('live-player--init');
			}
			if (livePlayer.classList.contains('live-player--fixed')) {
				livePlayer.classList.remove('live-player--fixed');
			}
			livePlayer.classList.add('live-player--mobile');
		}
	}

	/**
	 * adds some styles to the live player that would be called at desktop breakpoints. This is added specifically to
	 * deal with a window being resized.
	 */
	function livePlayerDesktopReset() {
		if (body.classList.contains('live-player--open')) {
			body.classList.remove('live-player--open');
		}
		if (livePlayer.classList.contains('live-player--mobile')) {
			livePlayer.classList.remove('live-player--mobile');
		}
		liveLinksMobileState();
		setTimeout(getScrollPosition, 1000);
		if (window.innerWidth >= 1385 || this.document.documentElement.clientWidth >= 1385 || this.document.body.clientWidth >= 1385) {
			livePlayer.style.right = 'calc(50% - 700px)';
		} else {
			livePlayer.style.right = '0';
		}
	}

	/**
	 * creates a re-usable variable that will call a button name, element to hide, and element to display
	 *
	 * @param btn
	 * @param elemHide
	 * @param elemDisplay
	 */
	var lpAction = function (btn, elemHide, elemDisplay) {
		this.btn = btn;
		this.elemHide = elemHide;
		this.elemDisplay = elemDisplay;
	};

	/**
	 * this function will create a re-usable function to hide and display elements based on lpAction
	 */
	lpAction.prototype.playAction = function () {
		var that = this; // `this`, when registering an event handler, won't ref the method's parent object, so a var it is
		addEventHandler(that.btn, elemClick, function () {
			that.elemHide.style.display = 'none';
			that.elemDisplay.style.display = 'inline-block';
		});
	};

	/**
	 * variables used for button interactions on the live player
	 */
	var playLp, pauseLp, resumeLp, playBtn, pauseBtn, resumeBtn, lpListenNow, lpNowPlaying;
	playBtn = document.getElementById('playButton');
	pauseBtn = document.getElementById('pauseButton');
	resumeBtn = document.getElementById('resumeButton');
	lpListenNow = document.getElementById('live-stream__listen-now');
	lpNowPlaying = document.getElementById('live-stream__now-playing');

	/**
	 * creates new method of lpAction with custom btn, element to hide, and element to display
	 *
	 * @type {lpAction}
	 */
	resumeLp = new lpAction(resumeBtn, lpListenNow, lpNowPlaying);

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	function toggleNavButton() {
		body.classList.toggle('mobile-nav--open');
	}

	addEventHandler(mobileNavButton, elemClick, toggleNavButton);

	/**
	 * Toggles a target element.
	 *
	 * @param {MouseEvent} e
	 */
	function toggleCollapsedElement(e) {
		var target = $($(this).attr('data-target')).get(0),
			currentText = $(this).html(),
			newText = $(this).attr('data-alt-text');

		e.preventDefault();

		target.style.display = target.style.display != 'none' ? 'none' : 'block';

		$(this).html(newText);
		$(this).attr('data-alt-text', currentText);
	}

	if (collapseToggle != null) {
		$(collapseToggle ).click(toggleCollapsedElement);
	}

	/**
	 * Toggles a class to the Live Play Stream Select box when the box is clicked
	 */
	function toggleStreamSelect() {
		livePlayerStreamSelect.classList.toggle('open');
		livePlayerStream.classList.toggle('open');
	}

	addEventHandler(livePlayerStreamSelect, elemClick, toggleStreamSelect);

	/**
	 * Selects a Live Player Stream
	 */
	function selectStream() {
		var selected_stream = this.querySelector('.live-player__stream--name').textContent;

		livePlayerCurrentName.textContent = selected_stream;
		document.dispatchEvent(new CustomEvent('live-player-stream-changed', {'detail': selected_stream}));
	}

	for (var i = 0; i < livePlayerStreams.length; i++) {
		addEventHandler(livePlayerStreams[i], elemClick, selectStream);
	}

	function liveLinksMobileState() {
		if ( $('body').hasClass('live-player--open')) {
			document.body.style.overflow = 'hidden';
			html.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = 'initial';
			html.style.overflow = 'initial';
		}
	}

	/**
	 * Toggles a class to the body when an element is clicked on small screens.
	 */
	function openLivePlayer() {
		if (window.innerWidth <= 767) {
			body.classList.toggle('live-player--open');
			liveLinksMobileState();
		}
	}

	/**
	 * Closes the live links
	 */
	function liveLinksClose() {
		if (window.innerWidth <= 767) {
			if (body.classList.contains('live-player--open')) {
				body.classList.remove('live-player--open');
			}
			liveLinksMobileState();
		}
	}

	function playerActive() {
		body.classList.add('live-player--active');
	}

	function playerNotActive() {
		body.classList.remove('live-player--active');
	}

	/**
	 * Resize Window function for when a user scales down their browser window below 767px
	 */
	function resizeWindow() {
		if (window.innerWidth <= 767) {
			if (livePlayer != null) {
				livePlayerMobileReset();
			}
		} else {
			if (livePlayer != null) {
				livePlayerDesktopReset();
				addEventHandler(window, elemScroll, function () {
					scrollDebounce();
					scrollThrottle();
				});
			}
		}
	}

	/**
	 * variables that define debounce and throttling for window resizing and scrolling
	 */
	var scrollDebounce = _.debounce(getScrollPosition, 50),
		scrollThrottle = _.throttle(getScrollPosition, 50),
		resizeDebounce = _.debounce(resizeWindow, 50),
		resizeThrottle = _.throttle(resizeWindow, 50);

	/**
	 * functions being run at specific window widths.
	 */
	if (window.innerWidth >= 768) {
		addEventHandler(window, elemLoad, function () {
			lpPosBase();
		});
		addEventHandler(window, elemScroll, function () {
			scrollDebounce();
			scrollThrottle();
		});
	}

	if (onAir != null) {
		addEventHandler(onAir, elemClick, openLivePlayer);
	}
	if (upNext != null) {
		addEventHandler(upNext, elemClick, openLivePlayer);
	}
	if (nowPlaying != null) {
		addEventHandler(nowPlaying, elemClick, openLivePlayer);
	}
	if (livePlayerMore != null) {
		addEventHandler(livePlayerMore, 'click', openLivePlayer);
	}
	if (liveLinksWidget != null) {
		addEventHandler(liveLinksWidget, elemClick, liveLinksClose);
	}
	if (playBtn != null || resumeBtn != null) {
		addEventHandler(playBtn, elemClick, playerActive);
		addEventHandler(resumeBtn, elemClick, playerActive);
	}
	if (pauseBtn != null) {
		addEventHandler(pauseBtn, elemClick, playerNotActive);
	}

	addEventHandler(window, elemResize, function () {
		resizeDebounce();
		resizeThrottle();
	});

	function init_menu_overlay() {
		var $menu = jQuery(document.querySelector('.header__nav--list')),
			$secondary = jQuery(document.querySelector('.header__secondary')),
			$overlay = jQuery(document.querySelector('.menu-overlay-mask'));

		$menu.on('mouseover', '.menu-item-has-children, .header__account--small', function (e) {
			$overlay.addClass('is-visible');
		});
		$menu.on('mouseout', '.menu-item-has-children, .header__account--small', function (e) {
			$overlay.removeClass('is-visible');
		});

		$secondary.on('mouseover', '.header__account--small, .header__account--large.logged-in', function (e) {
			$overlay.addClass('is-visible');
		});
		$secondary.on('mouseout', '.header__account--small, .header__account--large.logged-in', function (e) {
			$overlay.removeClass('is-visible');
		});
	}

	init_menu_overlay();

	jQuery(function ($) {
		$('.popup').on('click', function (ev) {
			ev.preventDefault();
			var x = screen.width / 2 - 700 / 2;
			var y = screen.height / 2 - 450 / 2;
			window.open($(this).attr('href'), $(this).attr('href'), 'height=485,width=700,scrollbars=yes, resizable=yes,left=' + x + ',top=' + y);
		});
	});

	function personality_toggle() {
		var $button = jQuery('.person-toggle');
		start = jQuery('.personality__meta').first().height(); // get the height of the meta before we start, basically tells us whether we're using the mobile or desktop height

		$button.on('click', function (e) {
			var $this = $(this);
			$parent = $this.parent().parent('.personality');
			$meta = $this.siblings('.personality__meta');
			curr = $meta.height();
			auto = $meta.css('height', 'auto').height(),
				offset = '';

			$parent.toggleClass('open');
			// if( $parent.hasClass('open') ) {
			// 	$meta.height(curr).animate({height: auto * 0.69}, 1000); // the 0.69 adjusts for the difference in height due to the overflow: visible wrapping the text
			// } else {
			// 	$meta.height(curr).animate({height: start}, 1000);
			// }


			if ($this.hasClass('active')) {
				$this.text('More');
			} else {
				$this.text('Less');
			}
			$this.toggleClass('active');
		});
	}

	personality_toggle();

})();

(function() {
	var $ = jQuery,
		$searchContainer = $( '#header__search--form '),
		$searchForm = $( '#header__search--form ' ).find( 'form' ),
		$searchBtn = $( '#header__search'),
		$searchInput = $( '#header-search' ),
		$overlay = $('.header-search-overlay-mask' );
	
	/**
	 * A function to show the header search when an event is targeted.
	 *
	 * @param e
	 */
	function showSearch(e) {
		e.preventDefault();
		
		if ( $searchContainer.hasClass( 'header__search--open' ) ) {
			return; 
		}
		
		$overlay.addClass( 'is-visible' )
		
		// Now, show the search form, but don't set focus until the transition
		// animation is complete. This is because Webkit browsers scroll to 
		// the element when it gets focus, and they scroll to it where it was
		// before the transition started.		
		if ( '0s' !== $searchContainer.css('transitionDuration') ) {
			$searchContainer.one('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function () {
				$searchInput.focus().select();
			} );
		} else {
			$searchInput.focus().select();			
		}
		$searchContainer.addClass('header__search--open'); 
	}
	
	/**
	 * A function to hide the header search when an event is targeted.
	 *
	 * @param e
	 */
	function closeSearch(e) {
		e.preventDefault();
		
		if ( ! $searchContainer.hasClass( 'header__search--open' ) ) {
			return;
		}
		
		$searchContainer.removeClass( 'header__search--open' );
		$overlay.removeClass('is-visible');
		document.activeElement.blur();
	}
	
	/**
	 * Event listeners to run on click to show and close the search.
	 */
	$searchBtn.click( showSearch ); 
	
	/**
	 * Open search if user clicks on it.
	 */
	$searchInput.add( $searchForm.find( 'button[type=submit]' ) ).click( showSearch );
	
	function checkSearchField () {
		var $search_body = $searchContainer.find( '.header-search-body' );
		
		// Show the body only if there's text in the search field.
		if ( $searchInput.val().length ) {
			$search_body.addClass( 'is-visible' );
		} else {
			$search_body.removeClass( 'is-visible' );
		}
	}
	
	$searchInput.keyup( checkSearchField );
	
	checkSearchField(); 
	
	/**
	 * Close the search box when user presses escape.
	 */
	$(window).keydown(function (e) {
		if (e.keyCode === 27){
			closeSearch(e);
		}		
	});
	
	/**
	 * Handle enter key for Safari. 
	 */
	$searchForm.keydown( function ( e ) {
		if ( 13 === e.keyCode ) {
			$( this ).submit(); 
		}
	} );
	
	/**
	 * Close the search box (if open) if the user clicks on the overlay.
	 */
	$overlay.click(function (e) {
		closeSearch(e);
	});
	
	/**
	 * Close the search box (if open) if the user clicks the close button.
	 */
	$searchContainer.find( '.header__search--cancel' ).click( function ( e ) {
		e.preventDefault();
		closeSearch( e );
	} );
	
	/**
	 * Make "Search All Content" button trigger form submit.
	 */
	$searchContainer.find( '.header-search__search-all-btn' ).click( function () {
		$searchForm.submit(); 	
	} );
	
	/**
	 * PJAX workaround. PJAX is set to only handle links when they're clicked,
	 * so to get the form to work over PJAX we need to create a fake link and 
	 * then click it. Clunky but it is the quick fix for now. 
	 * 
	 * Note that we are calling click() on the DOM object, not the jQuery 
	 * object. This is the only way to get this to work on Safari. 
	 */
	$searchForm.submit( function ( e ) {
		e.preventDefault();		
		
		$( '<a></a>' )
			.attr( 'href', $( this ).attr( 'action' ) + '?s=' + $( this ).find( 'input[name=s]' ).val() )
			.appendTo( $( this ) )
			.get( 0 ).click() // Note we are triggering click on the DOM object, not the jQuery object.
		;
		
		closeSearch( e );
	} );
	
})();