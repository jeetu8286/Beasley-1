/*!
* jQuery Cycle2; version: 2.1.6 build: 20141007
* http://jquery.malsup.com/cycle2/
* Copyright (c) 2014 M. Alsup; Dual licensed: MIT/GPL
*/

/* Cycle2 core engine */
;(function($) {
"use strict";

var version = '2.1.6';

$.fn.cycle = function( options ) {
    // fix mistakes with the ready state
    var o;
    if ( this.length === 0 && !$.isReady ) {
        o = { s: this.selector, c: this.context };
        $.fn.cycle.log('requeuing slideshow (dom not ready)');
        $(function() {
            $( o.s, o.c ).cycle(options);
        });
        return this;
    }

    return this.each(function() {
        var data, opts, shortName, val;
        var container = $(this);
        var log = $.fn.cycle.log;

        if ( container.data('cycle.opts') )
            return; // already initialized

        if ( container.data('cycle-log') === false || 
            ( options && options.log === false ) ||
            ( opts && opts.log === false) ) {
            log = $.noop;
        }

        log('--c2 init--');
        data = container.data();
        for (var p in data) {
            // allow props to be accessed sans 'cycle' prefix and log the overrides
            if (data.hasOwnProperty(p) && /^cycle[A-Z]+/.test(p) ) {
                val = data[p];
                shortName = p.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, lowerCase);
                log(shortName+':', val, '('+typeof val +')');
                data[shortName] = val;
            }
        }

        opts = $.extend( {}, $.fn.cycle.defaults, data, options || {});

        opts.timeoutId = 0;
        opts.paused = opts.paused || false; // #57
        opts.container = container;
        opts._maxZ = opts.maxZ;

        opts.API = $.extend ( { _container: container }, $.fn.cycle.API );
        opts.API.log = log;
        opts.API.trigger = function( eventName, args ) {
            opts.container.trigger( eventName, args );
            return opts.API;
        };

        container.data( 'cycle.opts', opts );
        container.data( 'cycle.API', opts.API );

        // opportunity for plugins to modify opts and API
        opts.API.trigger('cycle-bootstrap', [ opts, opts.API ]);

        opts.API.addInitialSlides();
        opts.API.preInitSlideshow();

        if ( opts.slides.length )
            opts.API.initSlideshow();
    });
};

$.fn.cycle.API = {
    opts: function() {
        return this._container.data( 'cycle.opts' );
    },
    addInitialSlides: function() {
        var opts = this.opts();
        var slides = opts.slides;
        opts.slideCount = 0;
        opts.slides = $(); // empty set
        
        // add slides that already exist
        slides = slides.jquery ? slides : opts.container.find( slides );

        if ( opts.random ) {
            slides.sort(function() {return Math.random() - 0.5;});
        }

        opts.API.add( slides );
    },

    preInitSlideshow: function() {
        var opts = this.opts();
        opts.API.trigger('cycle-pre-initialize', [ opts ]);
        var tx = $.fn.cycle.transitions[opts.fx];
        if (tx && $.isFunction(tx.preInit))
            tx.preInit( opts );
        opts._preInitialized = true;
    },

    postInitSlideshow: function() {
        var opts = this.opts();
        opts.API.trigger('cycle-post-initialize', [ opts ]);
        var tx = $.fn.cycle.transitions[opts.fx];
        if (tx && $.isFunction(tx.postInit))
            tx.postInit( opts );
    },

    initSlideshow: function() {
        var opts = this.opts();
        var pauseObj = opts.container;
        var slideOpts;
        opts.API.calcFirstSlide();

        if ( opts.container.css('position') == 'static' )
            opts.container.css('position', 'relative');

        $(opts.slides[opts.currSlide]).css({
            opacity: 1,
            display: 'block',
            visibility: 'visible'
        });
        opts.API.stackSlides( opts.slides[opts.currSlide], opts.slides[opts.nextSlide], !opts.reverse );

        if ( opts.pauseOnHover ) {
            // allow pauseOnHover to specify an element
            if ( opts.pauseOnHover !== true )
                pauseObj = $( opts.pauseOnHover );

            pauseObj.hover(
                function(){ opts.API.pause( true ); }, 
                function(){ opts.API.resume( true ); }
            );
        }

        // stage initial transition
        if ( opts.timeout ) {
            slideOpts = opts.API.getSlideOpts( opts.currSlide );
            opts.API.queueTransition( slideOpts, slideOpts.timeout + opts.delay );
        }

        opts._initialized = true;
        opts.API.updateView( true );
        opts.API.trigger('cycle-initialized', [ opts ]);
        opts.API.postInitSlideshow();
    },

    pause: function( hover ) {
        var opts = this.opts(),
            slideOpts = opts.API.getSlideOpts(),
            alreadyPaused = opts.hoverPaused || opts.paused;

        if ( hover )
            opts.hoverPaused = true; 
        else
            opts.paused = true;

        if ( ! alreadyPaused ) {
            opts.container.addClass('cycle-paused');
            opts.API.trigger('cycle-paused', [ opts ]).log('cycle-paused');

            if ( slideOpts.timeout ) {
                clearTimeout( opts.timeoutId );
                opts.timeoutId = 0;
                
                // determine how much time is left for the current slide
                opts._remainingTimeout -= ( $.now() - opts._lastQueue );
                if ( opts._remainingTimeout < 0 || isNaN(opts._remainingTimeout) )
                    opts._remainingTimeout = undefined;
            }
        }
    },

    resume: function( hover ) {
        var opts = this.opts(),
            alreadyResumed = !opts.hoverPaused && !opts.paused,
            remaining;

        if ( hover )
            opts.hoverPaused = false; 
        else
            opts.paused = false;

    
        if ( ! alreadyResumed ) {
            opts.container.removeClass('cycle-paused');
            // #gh-230; if an animation is in progress then don't queue a new transition; it will
            // happen naturally
            if ( opts.slides.filter(':animated').length === 0 )
                opts.API.queueTransition( opts.API.getSlideOpts(), opts._remainingTimeout );
            opts.API.trigger('cycle-resumed', [ opts, opts._remainingTimeout ] ).log('cycle-resumed');
        }
    },

    add: function( slides, prepend ) {
        var opts = this.opts();
        var oldSlideCount = opts.slideCount;
        var startSlideshow = false;
        var len;

        if ( $.type(slides) == 'string')
            slides = $.trim( slides );

        $( slides ).each(function(i) {
            var slideOpts;
            var slide = $(this);

            if ( prepend )
                opts.container.prepend( slide );
            else
                opts.container.append( slide );

            opts.slideCount++;
            slideOpts = opts.API.buildSlideOpts( slide );

            if ( prepend )
                opts.slides = $( slide ).add( opts.slides );
            else
                opts.slides = opts.slides.add( slide );

            opts.API.initSlide( slideOpts, slide, --opts._maxZ );

            slide.data('cycle.opts', slideOpts);
            opts.API.trigger('cycle-slide-added', [ opts, slideOpts, slide ]);
        });

        opts.API.updateView( true );

        startSlideshow = opts._preInitialized && (oldSlideCount < 2 && opts.slideCount >= 1);
        if ( startSlideshow ) {
            if ( !opts._initialized )
                opts.API.initSlideshow();
            else if ( opts.timeout ) {
                len = opts.slides.length;
                opts.nextSlide = opts.reverse ? len - 1 : 1;
                if ( !opts.timeoutId ) {
                    opts.API.queueTransition( opts );
                }
            }
        }
    },

    calcFirstSlide: function() {
        var opts = this.opts();
        var firstSlideIndex;
        firstSlideIndex = parseInt( opts.startingSlide || 0, 10 );
        if (firstSlideIndex >= opts.slides.length || firstSlideIndex < 0)
            firstSlideIndex = 0;

        opts.currSlide = firstSlideIndex;
        if ( opts.reverse ) {
            opts.nextSlide = firstSlideIndex - 1;
            if (opts.nextSlide < 0)
                opts.nextSlide = opts.slides.length - 1;
        }
        else {
            opts.nextSlide = firstSlideIndex + 1;
            if (opts.nextSlide == opts.slides.length)
                opts.nextSlide = 0;
        }
    },

    calcNextSlide: function() {
        var opts = this.opts();
        var roll;
        if ( opts.reverse ) {
            roll = (opts.nextSlide - 1) < 0;
            opts.nextSlide = roll ? opts.slideCount - 1 : opts.nextSlide-1;
            opts.currSlide = roll ? 0 : opts.nextSlide+1;
        }
        else {
            roll = (opts.nextSlide + 1) == opts.slides.length;
            opts.nextSlide = roll ? 0 : opts.nextSlide+1;
            opts.currSlide = roll ? opts.slides.length-1 : opts.nextSlide-1;
        }
    },

    calcTx: function( slideOpts, manual ) {
        var opts = slideOpts;
        var tx;

        if ( opts._tempFx )
            tx = $.fn.cycle.transitions[opts._tempFx];
        else if ( manual && opts.manualFx )
            tx = $.fn.cycle.transitions[opts.manualFx];

        if ( !tx )
            tx = $.fn.cycle.transitions[opts.fx];

        opts._tempFx = null;
        this.opts()._tempFx = null;

        if (!tx) {
            tx = $.fn.cycle.transitions.fade;
            opts.API.log('Transition "' + opts.fx + '" not found.  Using fade.');
        }
        return tx;
    },

    prepareTx: function( manual, fwd ) {
        var opts = this.opts();
        var after, curr, next, slideOpts, tx;

        if ( opts.slideCount < 2 ) {
            opts.timeoutId = 0;
            return;
        }
        if ( manual && ( !opts.busy || opts.manualTrump ) ) {
            opts.API.stopTransition();
            opts.busy = false;
            clearTimeout(opts.timeoutId);
            opts.timeoutId = 0;
        }
        if ( opts.busy )
            return;
        if ( opts.timeoutId === 0 && !manual )
            return;

        curr = opts.slides[opts.currSlide];
        next = opts.slides[opts.nextSlide];
        slideOpts = opts.API.getSlideOpts( opts.nextSlide );
        tx = opts.API.calcTx( slideOpts, manual );

        opts._tx = tx;

        if ( manual && slideOpts.manualSpeed !== undefined )
            slideOpts.speed = slideOpts.manualSpeed;

        // if ( opts.nextSlide === opts.currSlide )
        //     opts.API.calcNextSlide();

        // ensure that:
        //      1. advancing to a different slide
        //      2. this is either a manual event (prev/next, pager, cmd) or 
        //              a timer event and slideshow is not paused
        if ( opts.nextSlide != opts.currSlide && 
            (manual || (!opts.paused && !opts.hoverPaused && opts.timeout) )) { // #62

            opts.API.trigger('cycle-before', [ slideOpts, curr, next, fwd ]);
            if ( tx.before )
                tx.before( slideOpts, curr, next, fwd );

            after = function() {
                opts.busy = false;
                // #76; bail if slideshow has been destroyed
                if (! opts.container.data( 'cycle.opts' ) )
                    return;

                if (tx.after)
                    tx.after( slideOpts, curr, next, fwd );
                opts.API.trigger('cycle-after', [ slideOpts, curr, next, fwd ]);
                opts.API.queueTransition( slideOpts);
                opts.API.updateView( true );
            };

            opts.busy = true;
            if (tx.transition)
                tx.transition(slideOpts, curr, next, fwd, after);
            else
                opts.API.doTransition( slideOpts, curr, next, fwd, after);

            opts.API.calcNextSlide();
            opts.API.updateView();
        } else {
            opts.API.queueTransition( slideOpts );
        }
    },

    // perform the actual animation
    doTransition: function( slideOpts, currEl, nextEl, fwd, callback) {
        var opts = slideOpts;
        var curr = $(currEl), next = $(nextEl);
        var fn = function() {
            // make sure animIn has something so that callback doesn't trigger immediately
            next.animate(opts.animIn || { opacity: 1}, opts.speed, opts.easeIn || opts.easing, callback);
        };

        next.css(opts.cssBefore || {});
        curr.animate(opts.animOut || {}, opts.speed, opts.easeOut || opts.easing, function() {
            curr.css(opts.cssAfter || {});
            if (!opts.sync) {
                fn();
            }
        });
        if (opts.sync) {
            fn();
        }
    },

    queueTransition: function( slideOpts, specificTimeout ) {
        var opts = this.opts();
        var timeout = specificTimeout !== undefined ? specificTimeout : slideOpts.timeout;
        if (opts.nextSlide === 0 && --opts.loop === 0) {
            opts.API.log('terminating; loop=0');
            opts.timeout = 0;
            if ( timeout ) {
                setTimeout(function() {
                    opts.API.trigger('cycle-finished', [ opts ]);
                }, timeout);
            }
            else {
                opts.API.trigger('cycle-finished', [ opts ]);
            }
            // reset nextSlide
            opts.nextSlide = opts.currSlide;
            return;
        }
        if ( opts.continueAuto !== undefined ) {
            if ( opts.continueAuto === false || 
                ($.isFunction(opts.continueAuto) && opts.continueAuto() === false )) {
                opts.API.log('terminating automatic transitions');
                opts.timeout = 0;
                if ( opts.timeoutId )
                    clearTimeout(opts.timeoutId);
                return;
            }
        }
        if ( timeout ) {
            opts._lastQueue = $.now();
            if ( specificTimeout === undefined )
                opts._remainingTimeout = slideOpts.timeout;

            if ( !opts.paused && ! opts.hoverPaused ) {
                opts.timeoutId = setTimeout(function() { 
                    opts.API.prepareTx( false, !opts.reverse ); 
                }, timeout );
            }
        }
    },

    stopTransition: function() {
        var opts = this.opts();
        if ( opts.slides.filter(':animated').length ) {
            opts.slides.stop(false, true);
            opts.API.trigger('cycle-transition-stopped', [ opts ]);
        }

        if ( opts._tx && opts._tx.stopTransition )
            opts._tx.stopTransition( opts );
    },

    // advance slide forward or back
    advanceSlide: function( val ) {
        var opts = this.opts();
        clearTimeout(opts.timeoutId);
        opts.timeoutId = 0;
        opts.nextSlide = opts.currSlide + val;
        
        if (opts.nextSlide < 0)
            opts.nextSlide = opts.slides.length - 1;
        else if (opts.nextSlide >= opts.slides.length)
            opts.nextSlide = 0;

        opts.API.prepareTx( true,  val >= 0 );
        return false;
    },

    buildSlideOpts: function( slide ) {
        var opts = this.opts();
        var val, shortName;
        var slideOpts = slide.data() || {};
        for (var p in slideOpts) {
            // allow props to be accessed sans 'cycle' prefix and log the overrides
            if (slideOpts.hasOwnProperty(p) && /^cycle[A-Z]+/.test(p) ) {
                val = slideOpts[p];
                shortName = p.match(/^cycle(.*)/)[1].replace(/^[A-Z]/, lowerCase);
                opts.API.log('['+(opts.slideCount-1)+']', shortName+':', val, '('+typeof val +')');
                slideOpts[shortName] = val;
            }
        }

        slideOpts = $.extend( {}, $.fn.cycle.defaults, opts, slideOpts );
        slideOpts.slideNum = opts.slideCount;

        try {
            // these props should always be read from the master state object
            delete slideOpts.API;
            delete slideOpts.slideCount;
            delete slideOpts.currSlide;
            delete slideOpts.nextSlide;
            delete slideOpts.slides;
        } catch(e) {
            // no op
        }
        return slideOpts;
    },

    getSlideOpts: function( index ) {
        var opts = this.opts();
        if ( index === undefined )
            index = opts.currSlide;

        var slide = opts.slides[index];
        var slideOpts = $(slide).data('cycle.opts');
        return $.extend( {}, opts, slideOpts );
    },
    
    initSlide: function( slideOpts, slide, suggestedZindex ) {
        var opts = this.opts();
        slide.css( slideOpts.slideCss || {} );
        if ( suggestedZindex > 0 )
            slide.css( 'zIndex', suggestedZindex );

        // ensure that speed settings are sane
        if ( isNaN( slideOpts.speed ) )
            slideOpts.speed = $.fx.speeds[slideOpts.speed] || $.fx.speeds._default;
        if ( !slideOpts.sync )
            slideOpts.speed = slideOpts.speed / 2;

        slide.addClass( opts.slideClass );
    },

    updateView: function( isAfter, isDuring, forceEvent ) {
        var opts = this.opts();
        if ( !opts._initialized )
            return;
        var slideOpts = opts.API.getSlideOpts();
        var currSlide = opts.slides[ opts.currSlide ];

        if ( ! isAfter && isDuring !== true ) {
            opts.API.trigger('cycle-update-view-before', [ opts, slideOpts, currSlide ]);
            if ( opts.updateView < 0 )
                return;
        }

        if ( opts.slideActiveClass ) {
            opts.slides.removeClass( opts.slideActiveClass )
                .eq( opts.currSlide ).addClass( opts.slideActiveClass );
        }

        if ( isAfter && opts.hideNonActive )
            opts.slides.filter( ':not(.' + opts.slideActiveClass + ')' ).css('visibility', 'hidden');

        if ( opts.updateView === 0 ) {
            setTimeout(function() {
                opts.API.trigger('cycle-update-view', [ opts, slideOpts, currSlide, isAfter ]);
            }, slideOpts.speed / (opts.sync ? 2 : 1) );
        }

        if ( opts.updateView !== 0 )
            opts.API.trigger('cycle-update-view', [ opts, slideOpts, currSlide, isAfter ]);
        
        if ( isAfter )
            opts.API.trigger('cycle-update-view-after', [ opts, slideOpts, currSlide ]);
    },

    getComponent: function( name ) {
        var opts = this.opts();
        var selector = opts[name];
        if (typeof selector === 'string') {
            // if selector is a child, sibling combinator, adjancent selector then use find, otherwise query full dom
            return (/^\s*[\>|\+|~]/).test( selector ) ? opts.container.find( selector ) : $( selector );
        }
        if (selector.jquery)
            return selector;
        
        return $(selector);
    },

    stackSlides: function( curr, next, fwd ) {
        var opts = this.opts();
        if ( !curr ) {
            curr = opts.slides[opts.currSlide];
            next = opts.slides[opts.nextSlide];
            fwd = !opts.reverse;
        }

        // reset the zIndex for the common case:
        // curr slide on top,  next slide beneath, and the rest in order to be shown
        $(curr).css('zIndex', opts.maxZ);

        var i;
        var z = opts.maxZ - 2;
        var len = opts.slideCount;
        if (fwd) {
            for ( i = opts.currSlide + 1; i < len; i++ )
                $( opts.slides[i] ).css( 'zIndex', z-- );
            for ( i = 0; i < opts.currSlide; i++ )
                $( opts.slides[i] ).css( 'zIndex', z-- );
        }
        else {
            for ( i = opts.currSlide - 1; i >= 0; i-- )
                $( opts.slides[i] ).css( 'zIndex', z-- );
            for ( i = len - 1; i > opts.currSlide; i-- )
                $( opts.slides[i] ).css( 'zIndex', z-- );
        }

        $(next).css('zIndex', opts.maxZ - 1);
    },

    getSlideIndex: function( el ) {
        return this.opts().slides.index( el );
    }

}; // API

// default logger
$.fn.cycle.log = function log() {
    /*global console:true */
    if (window.console && console.log)
        console.log('[cycle2] ' + Array.prototype.join.call(arguments, ' ') );
};

$.fn.cycle.version = function() { return 'Cycle2: ' + version; };

// helper functions

function lowerCase(s) {
    return (s || '').toLowerCase();
}

// expose transition object
$.fn.cycle.transitions = {
    custom: {
    },
    none: {
        before: function( opts, curr, next, fwd ) {
            opts.API.stackSlides( next, curr, fwd );
            opts.cssBefore = { opacity: 1, visibility: 'visible', display: 'block' };
        }
    },
    fade: {
        before: function( opts, curr, next, fwd ) {
            var css = opts.API.getSlideOpts( opts.nextSlide ).slideCss || {};
            opts.API.stackSlides( curr, next, fwd );
            opts.cssBefore = $.extend(css, { opacity: 0, visibility: 'visible', display: 'block' });
            opts.animIn = { opacity: 1 };
            opts.animOut = { opacity: 0 };
        }
    },
    fadeout: {
        before: function( opts , curr, next, fwd ) {
            var css = opts.API.getSlideOpts( opts.nextSlide ).slideCss || {};
            opts.API.stackSlides( curr, next, fwd );
            opts.cssBefore = $.extend(css, { opacity: 1, visibility: 'visible', display: 'block' });
            opts.animOut = { opacity: 0 };
        }
    },
    scrollHorz: {
        before: function( opts, curr, next, fwd ) {
            opts.API.stackSlides( curr, next, fwd );
            var w = opts.container.css('overflow','hidden').width();
            opts.cssBefore = { left: fwd ? w : - w, top: 0, opacity: 1, visibility: 'visible', display: 'block' };
            opts.cssAfter = { zIndex: opts._maxZ - 2, left: 0 };
            opts.animIn = { left: 0 };
            opts.animOut = { left: fwd ? -w : w };
        }
    }
};

// @see: http://jquery.malsup.com/cycle2/api
$.fn.cycle.defaults = {
    allowWrap:        true,
    autoSelector:     '.cycle-slideshow[data-cycle-auto-init!=false]',
    delay:            0,
    easing:           null,
    fx:              'fade',
    hideNonActive:    true,
    loop:             0,
    manualFx:         undefined,
    manualSpeed:      undefined,
    manualTrump:      true,
    maxZ:             100,
    pauseOnHover:     false,
    reverse:          false,
    slideActiveClass: 'cycle-slide-active',
    slideClass:       'cycle-slide',
    slideCss:         { position: 'absolute', top: 0, left: 0 },
    slides:          '> img',
    speed:            500,
    startingSlide:    0,
    sync:             true,
    timeout:          4000,
    updateView:       0
};

// automatically find and run slideshows
$(document).ready(function() {
    $( $.fn.cycle.defaults.autoSelector ).cycle();
});

})(jQuery);

/*! Cycle2 autoheight plugin; Copyright (c) M.Alsup, 2012; version: 20130913 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    autoHeight: 0, // setting this option to false disables autoHeight logic
    autoHeightSpeed: 250,
    autoHeightEasing: null
});    

$(document).on( 'cycle-initialized', function( e, opts ) {
    var autoHeight = opts.autoHeight;
    var t = $.type( autoHeight );
    var resizeThrottle = null;
    var ratio;

    if ( t !== 'string' && t !== 'number' )
        return;

    // bind events
    opts.container.on( 'cycle-slide-added cycle-slide-removed', initAutoHeight );
    opts.container.on( 'cycle-destroyed', onDestroy );

    if ( autoHeight == 'container' ) {
        opts.container.on( 'cycle-before', onBefore );
    }
    else if ( t === 'string' && /\d+\:\d+/.test( autoHeight ) ) { 
        // use ratio
        ratio = autoHeight.match(/(\d+)\:(\d+)/);
        ratio = ratio[1] / ratio[2];
        opts._autoHeightRatio = ratio;
    }

    // if autoHeight is a number then we don't need to recalculate the sentinel
    // index on resize
    if ( t !== 'number' ) {
        // bind unique resize handler per slideshow (so it can be 'off-ed' in onDestroy)
        opts._autoHeightOnResize = function () {
            clearTimeout( resizeThrottle );
            resizeThrottle = setTimeout( onResize, 50 );
        };

        $(window).on( 'resize orientationchange', opts._autoHeightOnResize );
    }

    setTimeout( onResize, 30 );

    function onResize() {
        initAutoHeight( e, opts );
    }
});

function initAutoHeight( e, opts ) {
    var clone, height, sentinelIndex;
    var autoHeight = opts.autoHeight;

    if ( autoHeight == 'container' ) {
        height = $( opts.slides[ opts.currSlide ] ).outerHeight();
        opts.container.height( height );
    }
    else if ( opts._autoHeightRatio ) { 
        opts.container.height( opts.container.width() / opts._autoHeightRatio );
    }
    else if ( autoHeight === 'calc' || ( $.type( autoHeight ) == 'number' && autoHeight >= 0 ) ) {
        if ( autoHeight === 'calc' )
            sentinelIndex = calcSentinelIndex( e, opts );
        else if ( autoHeight >= opts.slides.length )
            sentinelIndex = 0;
        else 
            sentinelIndex = autoHeight;

        // only recreate sentinel if index is different
        if ( sentinelIndex == opts._sentinelIndex )
            return;

        opts._sentinelIndex = sentinelIndex;
        if ( opts._sentinel )
            opts._sentinel.remove();

        // clone existing slide as sentinel
        clone = $( opts.slides[ sentinelIndex ].cloneNode(true) );
        
        // #50; remove special attributes from cloned content
        clone.removeAttr( 'id name rel' ).find( '[id],[name],[rel]' ).removeAttr( 'id name rel' );

        clone.css({
            position: 'static',
            visibility: 'hidden',
            display: 'block'
        }).prependTo( opts.container ).addClass('cycle-sentinel cycle-slide').removeClass('cycle-slide-active');
        clone.find( '*' ).css( 'visibility', 'hidden' );

        opts._sentinel = clone;
    }
}    

function calcSentinelIndex( e, opts ) {
    var index = 0, max = -1;

    // calculate tallest slide index
    opts.slides.each(function(i) {
        var h = $(this).height();
        if ( h > max ) {
            max = h;
            index = i;
        }
    });
    return index;
}

function onBefore( e, opts, outgoing, incoming, forward ) {
    var h = $(incoming).outerHeight();
    opts.container.animate( { height: h }, opts.autoHeightSpeed, opts.autoHeightEasing );
}

function onDestroy( e, opts ) {
    if ( opts._autoHeightOnResize ) {
        $(window).off( 'resize orientationchange', opts._autoHeightOnResize );
        opts._autoHeightOnResize = null;
    }
    opts.container.off( 'cycle-slide-added cycle-slide-removed', initAutoHeight );
    opts.container.off( 'cycle-destroyed', onDestroy );
    opts.container.off( 'cycle-before', onBefore );

    if ( opts._sentinel ) {
        opts._sentinel.remove();
        opts._sentinel = null;
    }
}

})(jQuery);

/*! caption plugin for Cycle2;  version: 20130306 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    caption:          '> .cycle-caption',
    captionTemplate:  '{{slideNum}} / {{slideCount}}',
    overlay:          '> .cycle-overlay',
    overlayTemplate:  '<div>{{title}}</div><div>{{desc}}</div>',
    captionModule:    'caption'
});    

$(document).on( 'cycle-update-view', function( e, opts, slideOpts, currSlide ) {
    if ( opts.captionModule !== 'caption' )
        return;
    var el;
    $.each(['caption','overlay'], function() {
        var name = this; 
        var template = slideOpts[name+'Template'];
        var el = opts.API.getComponent( name );
        if( el.length && template ) {
            el.html( opts.API.tmpl( template, slideOpts, opts, currSlide ) );
            el.show();
        }
        else {
            el.hide();
        }
    });
});

$(document).on( 'cycle-destroyed', function( e, opts ) {
    var el;
    $.each(['caption','overlay'], function() {
        var name = this, template = opts[name+'Template'];
        if ( opts[name] && template ) {
            el = opts.API.getComponent( 'caption' );
            el.empty();
        }
    });
});

})(jQuery);

/*! command plugin for Cycle2;  version: 20140415 */
(function($) {
"use strict";

var c2 = $.fn.cycle;

$.fn.cycle = function( options ) {
    var cmd, cmdFn, opts;
    var args = $.makeArray( arguments );

    if ( $.type( options ) == 'number' ) {
        return this.cycle( 'goto', options );
    }

    if ( $.type( options ) == 'string' ) {
        return this.each(function() {
            var cmdArgs;
            cmd = options;
            opts = $(this).data('cycle.opts');

            if ( opts === undefined ) {
                c2.log('slideshow must be initialized before sending commands; "' + cmd + '" ignored');
                return;
            }
            else {
                cmd = cmd == 'goto' ? 'jump' : cmd; // issue #3; change 'goto' to 'jump' internally
                cmdFn = opts.API[ cmd ];
                if ( $.isFunction( cmdFn )) {
                    cmdArgs = $.makeArray( args );
                    cmdArgs.shift();
                    return cmdFn.apply( opts.API, cmdArgs );
                }
                else {
                    c2.log( 'unknown command: ', cmd );
                }
            }
        });
    }
    else {
        return c2.apply( this, arguments );
    }
};

// copy props
$.extend( $.fn.cycle, c2 );

$.extend( c2.API, {
    next: function() {
        var opts = this.opts();
        if ( opts.busy && ! opts.manualTrump )
            return;

        var count = opts.reverse ? -1 : 1;
        if ( opts.allowWrap === false && ( opts.currSlide + count ) >= opts.slideCount )
            return;

        opts.API.advanceSlide( count );
        opts.API.trigger('cycle-next', [ opts ]).log('cycle-next');
    },

    prev: function() {
        var opts = this.opts();
        if ( opts.busy && ! opts.manualTrump )
            return;
        var count = opts.reverse ? 1 : -1;
        if ( opts.allowWrap === false && ( opts.currSlide + count ) < 0 )
            return;

        opts.API.advanceSlide( count );
        opts.API.trigger('cycle-prev', [ opts ]).log('cycle-prev');
    },

    destroy: function() {
        this.stop(); //#204

        var opts = this.opts();
        var clean = $.isFunction( $._data ) ? $._data : $.noop;  // hack for #184 and #201
        clearTimeout(opts.timeoutId);
        opts.timeoutId = 0;
        opts.API.stop();
        opts.API.trigger( 'cycle-destroyed', [ opts ] ).log('cycle-destroyed');
        opts.container.removeData();
        clean( opts.container[0], 'parsedAttrs', false );

        // #75; remove inline styles
        if ( ! opts.retainStylesOnDestroy ) {
            opts.container.removeAttr( 'style' );
            opts.slides.removeAttr( 'style' );
            opts.slides.removeClass( opts.slideActiveClass );
        }
        opts.slides.each(function() {
            var slide = $(this);
            slide.removeData();
            slide.removeClass( opts.slideClass );
            clean( this, 'parsedAttrs', false );
        });
    },

    jump: function( index, fx ) {
        // go to the requested slide
        var fwd;
        var opts = this.opts();
        if ( opts.busy && ! opts.manualTrump )
            return;
        var num = parseInt( index, 10 );
        if (isNaN(num) || num < 0 || num >= opts.slides.length) {
            opts.API.log('goto: invalid slide index: ' + num);
            return;
        }
        if (num == opts.currSlide) {
            opts.API.log('goto: skipping, already on slide', num);
            return;
        }
        opts.nextSlide = num;
        clearTimeout(opts.timeoutId);
        opts.timeoutId = 0;
        opts.API.log('goto: ', num, ' (zero-index)');
        fwd = opts.currSlide < opts.nextSlide;
        opts._tempFx = fx;
        opts.API.prepareTx( true, fwd );
    },

    stop: function() {
        var opts = this.opts();
        var pauseObj = opts.container;
        clearTimeout(opts.timeoutId);
        opts.timeoutId = 0;
        opts.API.stopTransition();
        if ( opts.pauseOnHover ) {
            if ( opts.pauseOnHover !== true )
                pauseObj = $( opts.pauseOnHover );
            pauseObj.off('mouseenter mouseleave');
        }
        opts.API.trigger('cycle-stopped', [ opts ]).log('cycle-stopped');
    },

    reinit: function() {
        var opts = this.opts();
        opts.API.destroy();
        opts.container.cycle();
    },

    remove: function( index ) {
        var opts = this.opts();
        var slide, slideToRemove, slides = [], slideNum = 1;
        for ( var i=0; i < opts.slides.length; i++ ) {
            slide = opts.slides[i];
            if ( i == index ) {
                slideToRemove = slide;
            }
            else {
                slides.push( slide );
                $( slide ).data('cycle.opts').slideNum = slideNum;
                slideNum++;
            }
        }
        if ( slideToRemove ) {
            opts.slides = $( slides );
            opts.slideCount--;
            $( slideToRemove ).remove();
            if (index == opts.currSlide)
                opts.API.advanceSlide( 1 );
            else if ( index < opts.currSlide )
                opts.currSlide--;
            else
                opts.currSlide++;

            opts.API.trigger('cycle-slide-removed', [ opts, index, slideToRemove ]).log('cycle-slide-removed');
            opts.API.updateView();
        }
    }

});

// listen for clicks on elements with data-cycle-cmd attribute
$(document).on('click.cycle', '[data-cycle-cmd]', function(e) {
    // issue cycle command
    e.preventDefault();
    var el = $(this);
    var command = el.data('cycle-cmd');
    var context = el.data('cycle-context') || '.cycle-slideshow';
    $(context).cycle(command, el.data('cycle-arg'));
});


})(jQuery);

/*! hash plugin for Cycle2;  version: 20130905 */
(function($) {
"use strict";

$(document).on( 'cycle-pre-initialize', function( e, opts ) {
    onHashChange( opts, true );

    opts._onHashChange = function() {
        onHashChange( opts, false );
    };

    $( window ).on( 'hashchange', opts._onHashChange);
});

$(document).on( 'cycle-update-view', function( e, opts, slideOpts ) {
    if ( slideOpts.hash && ( '#' + slideOpts.hash ) != window.location.hash ) {
        opts._hashFence = true;
        window.location.hash = slideOpts.hash;
    }
});

$(document).on( 'cycle-destroyed', function( e, opts) {
    if ( opts._onHashChange ) {
        $( window ).off( 'hashchange', opts._onHashChange );
    }
});

function onHashChange( opts, setStartingSlide ) {
    var hash;
    if ( opts._hashFence ) {
        opts._hashFence = false;
        return;
    }
    
    hash = window.location.hash.substring(1);

    opts.slides.each(function(i) {
        if ( $(this).data( 'cycle-hash' ) == hash ) {
            if ( setStartingSlide === true ) {
                opts.startingSlide = i;
            }
            else {
                var fwd = opts.currSlide < i;
                opts.nextSlide = i;
                opts.API.prepareTx( true, fwd );
            }
            return false;
        }
    });
}

})(jQuery);

/*! loader plugin for Cycle2;  version: 20131121 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    loader: false
});

$(document).on( 'cycle-bootstrap', function( e, opts ) {
    var addFn;

    if ( !opts.loader )
        return;

    // override API.add for this slideshow
    addFn = opts.API.add;
    opts.API.add = add;

    function add( slides, prepend ) {
        var slideArr = [];
        if ( $.type( slides ) == 'string' )
            slides = $.trim( slides );
        else if ( $.type( slides) === 'array' ) {
            for (var i=0; i < slides.length; i++ )
                slides[i] = $(slides[i])[0];
        }

        slides = $( slides );
        var slideCount = slides.length;

        if ( ! slideCount )
            return;

        slides.css('visibility','hidden').appendTo('body').each(function(i) { // appendTo fixes #56
            var count = 0;
            var slide = $(this);
            var images = slide.is('img') ? slide : slide.find('img');
            slide.data('index', i);
            // allow some images to be marked as unimportant (and filter out images w/o src value)
            images = images.filter(':not(.cycle-loader-ignore)').filter(':not([src=""])');
            if ( ! images.length ) {
                --slideCount;
                slideArr.push( slide );
                return;
            }

            count = images.length;
            images.each(function() {
                // add images that are already loaded
                if ( this.complete ) {
                    imageLoaded();
                }
                else {
                    $(this).load(function() {
                        imageLoaded();
                    }).on("error", function() {
                        if ( --count === 0 ) {
                            // ignore this slide
                            opts.API.log('slide skipped; img not loaded:', this.src);
                            if ( --slideCount === 0 && opts.loader == 'wait') {
                                addFn.apply( opts.API, [ slideArr, prepend ] );
                            }
                        }
                    });
                }
            });

            function imageLoaded() {
                if ( --count === 0 ) {
                    --slideCount;
                    addSlide( slide );
                }
            }
        });

        if ( slideCount )
            opts.container.addClass('cycle-loading');
        

        function addSlide( slide ) {
            var curr;
            if ( opts.loader == 'wait' ) {
                slideArr.push( slide );
                if ( slideCount === 0 ) {
                    // #59; sort slides into original markup order
                    slideArr.sort( sorter );
                    addFn.apply( opts.API, [ slideArr, prepend ] );
                    opts.container.removeClass('cycle-loading');
                }
            }
            else {
                curr = $(opts.slides[opts.currSlide]);
                addFn.apply( opts.API, [ slide, prepend ] );
                curr.show();
                opts.container.removeClass('cycle-loading');
            }
        }

        function sorter(a, b) {
            return a.data('index') - b.data('index');
        }
    }
});

})(jQuery);

/*! pager plugin for Cycle2;  version: 20140415 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    pager:            '> .cycle-pager',
    pagerActiveClass: 'cycle-pager-active',
    pagerEvent:       'click.cycle',
    pagerEventBubble: undefined,
    pagerTemplate:    '<span>&bull;</span>'
});

$(document).on( 'cycle-bootstrap', function( e, opts, API ) {
    // add method to API
    API.buildPagerLink = buildPagerLink;
});

$(document).on( 'cycle-slide-added', function( e, opts, slideOpts, slideAdded ) {
    if ( opts.pager ) {
        opts.API.buildPagerLink ( opts, slideOpts, slideAdded );
        opts.API.page = page;
    }
});

$(document).on( 'cycle-slide-removed', function( e, opts, index, slideRemoved ) {
    if ( opts.pager ) {
        var pagers = opts.API.getComponent( 'pager' );
        pagers.each(function() {
            var pager = $(this);
            $( pager.children()[index] ).remove();
        });
    }
});

$(document).on( 'cycle-update-view', function( e, opts, slideOpts ) {
    var pagers;

    if ( opts.pager ) {
        pagers = opts.API.getComponent( 'pager' );
        pagers.each(function() {
           $(this).children().removeClass( opts.pagerActiveClass )
            .eq( opts.currSlide ).addClass( opts.pagerActiveClass );
        });
    }
});

$(document).on( 'cycle-destroyed', function( e, opts ) {
    var pager = opts.API.getComponent( 'pager' );

    if ( pager ) {
        pager.children().off( opts.pagerEvent ); // #202
        if ( opts.pagerTemplate )
            pager.empty();
    }
});

function buildPagerLink( opts, slideOpts, slide ) {
    var pagerLink;
    var pagers = opts.API.getComponent( 'pager' );
    pagers.each(function() {
        var pager = $(this);
        if ( slideOpts.pagerTemplate ) {
            var markup = opts.API.tmpl( slideOpts.pagerTemplate, slideOpts, opts, slide[0] );
            pagerLink = $( markup ).appendTo( pager );
        }
        else {
            pagerLink = pager.children().eq( opts.slideCount - 1 );
        }
        pagerLink.on( opts.pagerEvent, function(e) {
            if ( ! opts.pagerEventBubble )
                e.preventDefault();
            opts.API.page( pager, e.currentTarget);
        });
    });
}

function page( pager, target ) {
    /*jshint validthis:true */
    var opts = this.opts();
    if ( opts.busy && ! opts.manualTrump )
        return;

    var index = pager.children().index( target );
    var nextSlide = index;
    var fwd = opts.currSlide < nextSlide;
    if (opts.currSlide == nextSlide) {
        return; // no op, clicked pager for the currently displayed slide
    }
    opts.nextSlide = nextSlide;
    opts._tempFx = opts.pagerFx;
    opts.API.prepareTx( true, fwd );
    opts.API.trigger('cycle-pager-activated', [opts, pager, target ]);
}

})(jQuery);

/*! prevnext plugin for Cycle2;  version: 20140408 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    next:           '> .cycle-next',
    nextEvent:      'click.cycle',
    disabledClass:  'disabled',
    prev:           '> .cycle-prev',
    prevEvent:      'click.cycle',
    swipe:          false
});

$(document).on( 'cycle-initialized', function( e, opts ) {
    opts.API.getComponent( 'next' ).on( opts.nextEvent, function(e) {
        e.preventDefault();
        opts.API.next();
    });

    opts.API.getComponent( 'prev' ).on( opts.prevEvent, function(e) {
        e.preventDefault();
        opts.API.prev();
    });

    if ( opts.swipe ) {
        var nextEvent = opts.swipeVert ? 'swipeUp.cycle' : 'swipeLeft.cycle swipeleft.cycle';
        var prevEvent = opts.swipeVert ? 'swipeDown.cycle' : 'swipeRight.cycle swiperight.cycle';
        opts.container.on( nextEvent, function(e) {
            opts._tempFx = opts.swipeFx;
            opts.API.next();
        });
        opts.container.on( prevEvent, function() {
            opts._tempFx = opts.swipeFx;
            opts.API.prev();
        });
    }
});

$(document).on( 'cycle-update-view', function( e, opts, slideOpts, currSlide ) {
    if ( opts.allowWrap )
        return;

    var cls = opts.disabledClass;
    var next = opts.API.getComponent( 'next' );
    var prev = opts.API.getComponent( 'prev' );
    var prevBoundry = opts._prevBoundry || 0;
    var nextBoundry = (opts._nextBoundry !== undefined)?opts._nextBoundry:opts.slideCount - 1;

    if ( opts.currSlide == nextBoundry )
        next.addClass( cls ).prop( 'disabled', true );
    else
        next.removeClass( cls ).prop( 'disabled', false );

    if ( opts.currSlide === prevBoundry )
        prev.addClass( cls ).prop( 'disabled', true );
    else
        prev.removeClass( cls ).prop( 'disabled', false );
});


$(document).on( 'cycle-destroyed', function( e, opts ) {
    opts.API.getComponent( 'prev' ).off( opts.nextEvent );
    opts.API.getComponent( 'next' ).off( opts.prevEvent );
    opts.container.off( 'swipeleft.cycle swiperight.cycle swipeLeft.cycle swipeRight.cycle swipeUp.cycle swipeDown.cycle' );
});

})(jQuery);

/*! progressive loader plugin for Cycle2;  version: 20130315 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    progressive: false
});

$(document).on( 'cycle-pre-initialize', function( e, opts ) {
    if ( !opts.progressive )
        return;

    var API = opts.API;
    var nextFn = API.next;
    var prevFn = API.prev;
    var prepareTxFn = API.prepareTx;
    var type = $.type( opts.progressive );
    var slides, scriptEl;

    if ( type == 'array' ) {
        slides = opts.progressive;
    }
    else if ($.isFunction( opts.progressive ) ) {
        slides = opts.progressive( opts );
    }
    else if ( type == 'string' ) {
        scriptEl = $( opts.progressive );
        slides = $.trim( scriptEl.html() );
        if ( !slides )
            return;
        // is it json array?
        if ( /^(\[)/.test( slides ) ) {
            try {
                slides = $.parseJSON( slides );
            }
            catch(err) {
                API.log( 'error parsing progressive slides', err );
                return;
            }
        }
        else {
            // plain text, split on delimeter
            slides = slides.split( new RegExp( scriptEl.data('cycle-split') || '\n') );
            
            // #95; look for empty slide
            if ( ! slides[ slides.length - 1 ] )
                slides.pop();
        }
    }



    if ( prepareTxFn ) {
        API.prepareTx = function( manual, fwd ) {
            var index, slide;

            if ( manual || slides.length === 0 ) {
                prepareTxFn.apply( opts.API, [ manual, fwd ] );
                return;
            }

            if ( fwd && opts.currSlide == ( opts.slideCount-1) ) {
                slide = slides[ 0 ];
                slides = slides.slice( 1 );
                opts.container.one('cycle-slide-added', function(e, opts ) {
                    setTimeout(function() {
                        opts.API.advanceSlide( 1 );
                    },50);
                });
                opts.API.add( slide );
            }
            else if ( !fwd && opts.currSlide === 0 ) {
                index = slides.length-1;
                slide = slides[ index ];
                slides = slides.slice( 0, index );
                opts.container.one('cycle-slide-added', function(e, opts ) {
                    setTimeout(function() {
                        opts.currSlide = 1;
                        opts.API.advanceSlide( -1 );
                    },50);
                });
                opts.API.add( slide, true );
            }
            else {
                prepareTxFn.apply( opts.API, [ manual, fwd ] );
            }
        };
    }

    if ( nextFn ) {
        API.next = function() {
            var opts = this.opts();
            if ( slides.length && opts.currSlide == ( opts.slideCount - 1 ) ) {
                var slide = slides[ 0 ];
                slides = slides.slice( 1 );
                opts.container.one('cycle-slide-added', function(e, opts ) {
                    nextFn.apply( opts.API );
                    opts.container.removeClass('cycle-loading');
                });
                opts.container.addClass('cycle-loading');
                opts.API.add( slide );
            }
            else {
                nextFn.apply( opts.API );    
            }
        };
    }
    
    if ( prevFn ) {
        API.prev = function() {
            var opts = this.opts();
            if ( slides.length && opts.currSlide === 0 ) {
                var index = slides.length-1;
                var slide = slides[ index ];
                slides = slides.slice( 0, index );
                opts.container.one('cycle-slide-added', function(e, opts ) {
                    opts.currSlide = 1;
                    opts.API.advanceSlide( -1 );
                    opts.container.removeClass('cycle-loading');
                });
                opts.container.addClass('cycle-loading');
                opts.API.add( slide, true );
            }
            else {
                prevFn.apply( opts.API );
            }
        };
    }
});

})(jQuery);

/*! tmpl plugin for Cycle2;  version: 20121227 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    tmplRegex: '{{((.)?.*?)}}'
});

$.extend($.fn.cycle.API, {
    tmpl: function( str, opts /*, ... */) {
        var regex = new RegExp( opts.tmplRegex || $.fn.cycle.defaults.tmplRegex, 'g' );
        var args = $.makeArray( arguments );
        args.shift();
        return str.replace(regex, function(_, str) {
            var i, j, obj, prop, names = str.split('.');
            for (i=0; i < args.length; i++) {
                obj = args[i];
                if ( ! obj )
                    continue;
                if (names.length > 1) {
                    prop = obj;
                    for (j=0; j < names.length; j++) {
                        obj = prop;
                        prop = prop[ names[j] ] || str;
                    }
                } else {
                    prop = obj[str];
                }

                if ($.isFunction(prop))
                    return prop.apply(obj, args);
                if (prop !== undefined && prop !== null && prop != str)
                    return prop;
            }
            return str;
        });
    }
});    

})(jQuery);

/*! center plugin for Cycle2;  version: 20140121 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    centerHorz: false,
    centerVert: false
});

$(document).on( 'cycle-pre-initialize', function( e, opts ) {
    if ( !opts.centerHorz && !opts.centerVert )
        return;

    // throttle resize event
    var timeout, timeout2;

    $(window).on( 'resize orientationchange load', resize );
    
    opts.container.on( 'cycle-destroyed', destroy );

    opts.container.on( 'cycle-initialized cycle-slide-added cycle-slide-removed', function( e, opts, slideOpts, slide ) {
        resize();
    });

    adjustActive();

    function resize() {
        clearTimeout( timeout );
        timeout = setTimeout( adjustActive, 50 );
    }

    function destroy( e, opts ) {
        clearTimeout( timeout );
        clearTimeout( timeout2 );
        $( window ).off( 'resize orientationchange', resize );
    }

    function adjustAll() {
        opts.slides.each( adjustSlide ); 
    }

    function adjustActive() {
        /*jshint validthis: true */
        adjustSlide.apply( opts.container.find( '.' + opts.slideActiveClass ) );
        clearTimeout( timeout2 );
        timeout2 = setTimeout( adjustAll, 50 );
    }

    function adjustSlide() {
        /*jshint validthis: true */
        var slide = $(this);
        var contW = opts.container.width();
        var contH = opts.container.height();
        var w = slide.outerWidth();
        var h = slide.outerHeight();
        if (w) {
            if (opts.centerHorz && w <= contW)
                slide.css( 'marginLeft', (contW - w) / 2 );
            if (opts.centerVert && h <= contH)
                slide.css( 'marginTop', (contH - h) / 2 );
        }
    }
});

})(jQuery);

/*! swipe plugin for Cycle2;  version: 20121120 */
(function($) {
"use strict";

// this script adds support for touch events.  the logic is lifted from jQuery Mobile.
// if you have jQuery Mobile installed, you do NOT need this script

var supportTouch = 'ontouchend' in document;

$.event.special.swipe = $.event.special.swipe || {
    scrollSupressionThreshold: 10,   // More than this horizontal displacement, and we will suppress scrolling.
    durationThreshold: 1000,         // More time than this, and it isn't a swipe.
    horizontalDistanceThreshold: 30, // Swipe horizontal displacement must be more than this.
    verticalDistanceThreshold: 75,   // Swipe vertical displacement must be less than this.

    setup: function() {
        var $this = $( this );

        $this.bind( 'touchstart', function( event ) {
            var data = event.originalEvent.touches ? event.originalEvent.touches[ 0 ] : event;
            var stop, start = {
                time: ( new Date() ).getTime(),
                coords: [ data.pageX, data.pageY ],
                origin: $( event.target )
            };

            function moveHandler( event ) {
                if ( !start )
                    return;

                var data = event.originalEvent.touches ? event.originalEvent.touches[ 0 ] : event;

                stop = {
                    time: ( new Date() ).getTime(),
                    coords: [ data.pageX, data.pageY ]
                };

                // prevent scrolling
                if ( Math.abs( start.coords[ 0 ] - stop.coords[ 0 ] ) > $.event.special.swipe.scrollSupressionThreshold ) {
                    event.preventDefault();
                }
            }

            $this.bind( 'touchmove', moveHandler )
                .one( 'touchend', function( event ) {
                    $this.unbind( 'touchmove', moveHandler );

                    if ( start && stop ) {
                        if ( stop.time - start.time < $.event.special.swipe.durationThreshold &&
                                Math.abs( start.coords[ 0 ] - stop.coords[ 0 ] ) > $.event.special.swipe.horizontalDistanceThreshold &&
                                Math.abs( start.coords[ 1 ] - stop.coords[ 1 ] ) < $.event.special.swipe.verticalDistanceThreshold ) {

                            start.origin.trigger( "swipe" )
                                .trigger( start.coords[0] > stop.coords[ 0 ] ? "swipeleft" : "swiperight" );
                        }
                    }
                    start = stop = undefined;
                });
        });
    }
};

$.event.special.swipeleft = $.event.special.swipeleft || {
    setup: function() {
        $( this ).bind( 'swipe', $.noop );
    }
};
$.event.special.swiperight = $.event.special.swiperight || $.event.special.swipeleft;

})(jQuery);

/*! carousel transition plugin for Cycle2;  version: 20130528 */
(function($) {
"use strict";

$( document ).on('cycle-bootstrap', function( e, opts, API ) {
    if ( opts.fx !== 'carousel' )
        return;

    API.getSlideIndex = function( el ) {
        var slides = this.opts()._carouselWrap.children();
        var i = slides.index( el );
        return i % slides.length;
    };

    // override default 'next' function
    API.next = function() {
        var count = opts.reverse ? -1 : 1;
        if ( opts.allowWrap === false && ( opts.currSlide + count ) > opts.slideCount - opts.carouselVisible )
            return;
        opts.API.advanceSlide( count );
        opts.API.trigger('cycle-next', [ opts ]).log('cycle-next');
    };

});


$.fn.cycle.transitions.carousel = {
    // transition API impl
    preInit: function( opts ) {
        opts.hideNonActive = false;
        
        opts.container.on('cycle-destroyed', $.proxy(this.onDestroy, opts.API));
        // override default API implementation
        opts.API.stopTransition = this.stopTransition;

        // issue #10
        for (var i=0; i < opts.startingSlide; i++) {
            opts.container.append( opts.slides[0] );
        }        
    },

    // transition API impl
    postInit: function( opts ) {
        var i, j, slide, pagerCutoffIndex, wrap;
        var vert = opts.carouselVertical;
        if (opts.carouselVisible && opts.carouselVisible > opts.slideCount)
            opts.carouselVisible = opts.slideCount - 1;
        var visCount = opts.carouselVisible || opts.slides.length;
        var slideCSS = { display: vert ? 'block' : 'inline-block', position: 'static' };

        // required styles
        opts.container.css({ position: 'relative', overflow: 'hidden' });
        opts.slides.css( slideCSS );

        opts._currSlide = opts.currSlide;

        // wrap slides in a div; this div is what is animated
        wrap = $('<div class="cycle-carousel-wrap"></div>')
            .prependTo( opts.container )
            .css({ margin: 0, padding: 0, top: 0, left: 0, position: 'absolute' })
            .append( opts.slides );

        opts._carouselWrap = wrap;

        if ( !vert )
            wrap.css('white-space', 'nowrap');

        if ( opts.allowWrap !== false ) {
            // prepend and append extra slides so we don't see any empty space when we
            // near the end of the carousel.  for fluid containers, add even more clones
            // so there is plenty to fill the screen
            // @todo: optimzie this based on slide sizes

            for ( j=0; j < (opts.carouselVisible === undefined ? 2 : 1); j++ ) {
                for ( i=0; i < opts.slideCount; i++ ) {
                    wrap.append( opts.slides[i].cloneNode(true) );
                }
                i = opts.slideCount;
                while ( i-- ) { // #160, #209
                    wrap.prepend( opts.slides[i].cloneNode(true) );
                }
            }

            wrap.find('.cycle-slide-active').removeClass('cycle-slide-active');
            opts.slides.eq(opts.startingSlide).addClass('cycle-slide-active');
        }

        if ( opts.pager && opts.allowWrap === false ) {
            // hide "extra" pagers
            pagerCutoffIndex = opts.slideCount - visCount;
            $( opts.pager ).children().filter( ':gt('+pagerCutoffIndex+')' ).hide();
        }

        opts._nextBoundry = opts.slideCount - opts.carouselVisible;

        this.prepareDimensions( opts );
    },

    prepareDimensions: function( opts ) {
        var dim, offset, pagerCutoffIndex, tmp, j;
        var vert = opts.carouselVertical;
        var visCount = opts.carouselVisible || opts.slides.length;

        if ( opts.carouselFluid && opts.carouselVisible ) {
            if ( ! opts._carouselResizeThrottle ) {
            // fluid container AND fluid slides; slides need to be resized to fit container
                this.fluidSlides( opts );
            }
        }
        else if ( opts.carouselVisible && opts.carouselSlideDimension ) {
            dim = visCount * opts.carouselSlideDimension;
            opts.container[ vert ? 'height' : 'width' ]( dim );
        }
        else if ( opts.carouselVisible ) {
            dim = visCount * $(opts.slides[0])[vert ? 'outerHeight' : 'outerWidth'](true);
            opts.container[ vert ? 'height' : 'width' ]( dim );
        }
        // else {
        //     // fluid; don't size the container
        // }

        offset = ( opts.carouselOffset || 0 );
        if ( opts.allowWrap !== false ) {
            if ( opts.carouselSlideDimension ) {
                offset -= ( (opts.slideCount + opts.currSlide) * opts.carouselSlideDimension );
            }
            else {
                // calculate offset based on actual slide dimensions
                tmp = opts._carouselWrap.children();
                for (j=0; j < (opts.slideCount + opts.currSlide); j++) {
                    offset -= $(tmp[j])[vert?'outerHeight':'outerWidth'](true);
                }
            }
        }

        opts._carouselWrap.css( vert ? 'top' : 'left', offset );
    },

    fluidSlides: function( opts ) {
        var timeout;
        var slide = opts.slides.eq(0);
        var adjustment = slide.outerWidth() - slide.width();
        var prepareDimensions = this.prepareDimensions;

        // throttle resize event
        $(window).on( 'resize', resizeThrottle);

        opts._carouselResizeThrottle = resizeThrottle;
        onResize();

        function resizeThrottle() {
            clearTimeout( timeout );
            timeout = setTimeout( onResize, 20 );
        }

        function onResize() {
            opts._carouselWrap.stop( false, true );
            var slideWidth = opts.container.width() / opts.carouselVisible;
            slideWidth = Math.ceil( slideWidth - adjustment );
            opts._carouselWrap.children().width( slideWidth );
            if ( opts._sentinel )
                opts._sentinel.width( slideWidth );
            prepareDimensions( opts );
        }
    },

    // transition API impl
    transition: function( opts, curr, next, fwd, callback ) {
        var moveBy, props = {};
        var hops = opts.nextSlide - opts.currSlide;
        var vert = opts.carouselVertical;
        var speed = opts.speed;

        // handle all the edge cases for wrapping & non-wrapping
        if ( opts.allowWrap === false ) {
            fwd = hops > 0;
            var currSlide = opts._currSlide;
            var maxCurr = opts.slideCount - opts.carouselVisible;
            if ( hops > 0 && opts.nextSlide > maxCurr && currSlide == maxCurr ) {
                hops = 0;
            }
            else if ( hops > 0 && opts.nextSlide > maxCurr ) {
                hops = opts.nextSlide - currSlide - (opts.nextSlide - maxCurr);
            }
            else if ( hops < 0 && opts.currSlide > maxCurr && opts.nextSlide > maxCurr ) {
                hops = 0;
            }
            else if ( hops < 0 && opts.currSlide > maxCurr ) {
                hops += opts.currSlide - maxCurr;
            }
            else 
                currSlide = opts.currSlide;

            moveBy = this.getScroll( opts, vert, currSlide, hops );
            opts.API.opts()._currSlide = opts.nextSlide > maxCurr ? maxCurr : opts.nextSlide;
        }
        else {
            if ( fwd && opts.nextSlide === 0 ) {
                // moving from last slide to first
                moveBy = this.getDim( opts, opts.currSlide, vert );
                callback = this.genCallback( opts, fwd, vert, callback );
            }
            else if ( !fwd && opts.nextSlide == opts.slideCount - 1 ) {
                // moving from first slide to last
                moveBy = this.getDim( opts, opts.currSlide, vert );
                callback = this.genCallback( opts, fwd, vert, callback );
            }
            else {
                moveBy = this.getScroll( opts, vert, opts.currSlide, hops );
            }
        }

        props[ vert ? 'top' : 'left' ] = fwd ? ( "-=" + moveBy ) : ( "+=" + moveBy );

        // throttleSpeed means to scroll slides at a constant rate, rather than
        // a constant speed
        if ( opts.throttleSpeed )
            speed = (moveBy / $(opts.slides[0])[vert ? 'height' : 'width']() ) * opts.speed;

        opts._carouselWrap.animate( props, speed, opts.easing, callback );
    },

    getDim: function( opts, index, vert ) {
        var slide = $( opts.slides[index] );
        return slide[ vert ? 'outerHeight' : 'outerWidth'](true);
    },

    getScroll: function( opts, vert, currSlide, hops ) {
        var i, moveBy = 0;

        if (hops > 0) {
            for (i=currSlide; i < currSlide+hops; i++)
                moveBy += this.getDim( opts, i, vert);
        }
        else {
            for (i=currSlide; i > currSlide+hops; i--)
                moveBy += this.getDim( opts, i, vert);
        }
        return moveBy;
    },

    genCallback: function( opts, fwd, vert, callback ) {
        // returns callback fn that resets the left/top wrap position to the "real" slides
        return function() {
            var pos = $(opts.slides[opts.nextSlide]).position();
            var offset = 0 - pos[vert?'top':'left'] + (opts.carouselOffset || 0);
            opts._carouselWrap.css( opts.carouselVertical ? 'top' : 'left', offset );
            callback();
        };
    },

    // core API override
    stopTransition: function() {
        var opts = this.opts();
        opts.slides.stop( false, true );
        opts._carouselWrap.stop( false, true );
    },

    // core API supplement
    onDestroy: function( e ) {
        var opts = this.opts();
        if ( opts._carouselResizeThrottle )
            $( window ).off( 'resize', opts._carouselResizeThrottle );
        opts.slides.prependTo( opts.container );
        opts._carouselWrap.remove();
    }
};

})(jQuery);

(function(t){"use strict";function e(t,e,r){return t.addEventListener?t.addEventListener(e,r,!1):t.attachEvent?t.attachEvent("on"+e,r):void 0}function r(t,e){var r,n;for(r=0,n=t.length;n>r;r++)if(t[r]===e)return!0;return!1}function n(t,e){var r;t.createTextRange?(r=t.createTextRange(),r.move("character",e),r.select()):t.selectionStart&&(t.focus(),t.setSelectionRange(e,e))}function a(t,e){try{return t.type=e,!0}catch(r){return!1}}t.Placeholders={Utils:{addEventListener:e,inArray:r,moveCaret:n,changeType:a}}})(this),function(t){"use strict";function e(){}function r(){try{return document.activeElement}catch(t){}}function n(t,e){var r,n,a=!!e&&t.value!==e,u=t.value===t.getAttribute(V);return(a||u)&&"true"===t.getAttribute(D)?(t.removeAttribute(D),t.value=t.value.replace(t.getAttribute(V),""),t.className=t.className.replace(R,""),n=t.getAttribute(F),parseInt(n,10)>=0&&(t.setAttribute("maxLength",n),t.removeAttribute(F)),r=t.getAttribute(P),r&&(t.type=r),!0):!1}function a(t){var e,r,n=t.getAttribute(V);return""===t.value&&n?(t.setAttribute(D,"true"),t.value=n,t.className+=" "+I,r=t.getAttribute(F),r||(t.setAttribute(F,t.maxLength),t.removeAttribute("maxLength")),e=t.getAttribute(P),e?t.type="text":"password"===t.type&&M.changeType(t,"text")&&t.setAttribute(P,"password"),!0):!1}function u(t,e){var r,n,a,u,i,l,o;if(t&&t.getAttribute(V))e(t);else for(a=t?t.getElementsByTagName("input"):b,u=t?t.getElementsByTagName("textarea"):f,r=a?a.length:0,n=u?u.length:0,o=0,l=r+n;l>o;o++)i=r>o?a[o]:u[o-r],e(i)}function i(t){u(t,n)}function l(t){u(t,a)}function o(t){return function(){m&&t.value===t.getAttribute(V)&&"true"===t.getAttribute(D)?M.moveCaret(t,0):n(t)}}function c(t){return function(){a(t)}}function s(t){return function(e){return A=t.value,"true"===t.getAttribute(D)&&A===t.getAttribute(V)&&M.inArray(C,e.keyCode)?(e.preventDefault&&e.preventDefault(),!1):void 0}}function d(t){return function(){n(t,A),""===t.value&&(t.blur(),M.moveCaret(t,0))}}function g(t){return function(){t===r()&&t.value===t.getAttribute(V)&&"true"===t.getAttribute(D)&&M.moveCaret(t,0)}}function v(t){return function(){i(t)}}function p(t){t.form&&(T=t.form,"string"==typeof T&&(T=document.getElementById(T)),T.getAttribute(U)||(M.addEventListener(T,"submit",v(T)),T.setAttribute(U,"true"))),M.addEventListener(t,"focus",o(t)),M.addEventListener(t,"blur",c(t)),m&&(M.addEventListener(t,"keydown",s(t)),M.addEventListener(t,"keyup",d(t)),M.addEventListener(t,"click",g(t))),t.setAttribute(j,"true"),t.setAttribute(V,x),(m||t!==r())&&a(t)}var b,f,m,h,A,y,E,x,L,T,N,S,w,B=["text","search","url","tel","email","password","number","textarea"],C=[27,33,34,35,36,37,38,39,40,8,46],k="#ccc",I="placeholdersjs",R=RegExp("(?:^|\\s)"+I+"(?!\\S)"),V="data-placeholder-value",D="data-placeholder-active",P="data-placeholder-type",U="data-placeholder-submit",j="data-placeholder-bound",q="data-placeholder-focus",z="data-placeholder-live",F="data-placeholder-maxlength",G=document.createElement("input"),H=document.getElementsByTagName("head")[0],J=document.documentElement,K=t.Placeholders,M=K.Utils;if(K.nativeSupport=void 0!==G.placeholder,!K.nativeSupport){for(b=document.getElementsByTagName("input"),f=document.getElementsByTagName("textarea"),m="false"===J.getAttribute(q),h="false"!==J.getAttribute(z),y=document.createElement("style"),y.type="text/css",E=document.createTextNode("."+I+" { color:"+k+"; }"),y.styleSheet?y.styleSheet.cssText=E.nodeValue:y.appendChild(E),H.insertBefore(y,H.firstChild),w=0,S=b.length+f.length;S>w;w++)N=b.length>w?b[w]:f[w-b.length],x=N.attributes.placeholder,x&&(x=x.nodeValue,x&&M.inArray(B,N.type)&&p(N));L=setInterval(function(){for(w=0,S=b.length+f.length;S>w;w++)N=b.length>w?b[w]:f[w-b.length],x=N.attributes.placeholder,x?(x=x.nodeValue,x&&M.inArray(B,N.type)&&(N.getAttribute(j)||p(N),(x!==N.getAttribute(V)||"password"===N.type&&!N.getAttribute(P))&&("password"===N.type&&!N.getAttribute(P)&&M.changeType(N,"text")&&N.setAttribute(P,"password"),N.value===N.getAttribute(V)&&(N.value=x),N.setAttribute(V,x)))):N.getAttribute(D)&&(n(N),N.removeAttribute(V));h||clearInterval(L)},100)}M.addEventListener(t,"beforeunload",function(){K.disable()}),K.disable=K.nativeSupport?e:i,K.enable=K.nativeSupport?e:l}(this);
/*jshint browser:true */
/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* FORKED by Steve Meyers on 7/21/15 to ensure that Embedly embeds don't receive an extra div wrapped around them.
*
*
*/

;(function( $ ){

  'use strict';

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement("div");
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        'iframe[src*="player.vimeo.com"]:not(.embedly-card)',
        'iframe[src*="youtube.com"]:not(.embedly-card)',
        'iframe[src*="youtube-nocookie.com"]:not(.embedly-card)',
        'iframe[src*="kickstarter.com"][src*="video.html"]:not(.embedly-card)',
        'object',
        'embed'
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('id')){
          var videoID = 'fitvid' + Math.floor(Math.random()*999999);
          $this.attr('id', videoID);
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );

/*!
 * headroom.js v0.9.3 - Give your page some headroom. Hide your header until you need it
 * Copyright (c) 2016 Nick Williams - http://wicky.nillia.ms/headroom.js
 * License: MIT
 */

!function(a,b){"use strict";"function"==typeof define&&define.amd?define([],b):"object"==typeof exports?module.exports=b():a.Headroom=b()}(this,function(){"use strict";function a(a){this.callback=a,this.ticking=!1}function b(a){return a&&"undefined"!=typeof window&&(a===window||a.nodeType)}function c(a){if(arguments.length<=0)throw new Error("Missing arguments in extend function");var d,e,f=a||{};for(e=1;e<arguments.length;e++){var g=arguments[e]||{};for(d in g)"object"!=typeof f[d]||b(f[d])?f[d]=f[d]||g[d]:f[d]=c(f[d],g[d])}return f}function d(a){return a===Object(a)?a:{down:a,up:a}}function e(a,b){b=c(b,e.options),this.lastKnownScrollY=0,this.elem=a,this.tolerance=d(b.tolerance),this.classes=b.classes,this.offset=b.offset,this.scroller=b.scroller,this.initialised=!1,this.onPin=b.onPin,this.onUnpin=b.onUnpin,this.onTop=b.onTop,this.onNotTop=b.onNotTop,this.onBottom=b.onBottom,this.onNotBottom=b.onNotBottom}var f={bind:!!function(){}.bind,classList:"classList"in document.documentElement,rAF:!!(window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame)};return window.requestAnimationFrame=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame,a.prototype={constructor:a,update:function(){this.callback&&this.callback(),this.ticking=!1},requestTick:function(){this.ticking||(requestAnimationFrame(this.rafCallback||(this.rafCallback=this.update.bind(this))),this.ticking=!0)},handleEvent:function(){this.requestTick()}},e.prototype={constructor:e,init:function(){return e.cutsTheMustard?(this.debouncer=new a(this.update.bind(this)),this.elem.classList.add(this.classes.initial),setTimeout(this.attachEvent.bind(this),100),this):void 0},destroy:function(){var a=this.classes;this.initialised=!1,this.elem.classList.remove(a.unpinned,a.pinned,a.top,a.notTop,a.initial),this.scroller.removeEventListener("scroll",this.debouncer,!1)},attachEvent:function(){this.initialised||(this.lastKnownScrollY=this.getScrollY(),this.initialised=!0,this.scroller.addEventListener("scroll",this.debouncer,!1),this.debouncer.handleEvent())},unpin:function(){var a=this.elem.classList,b=this.classes;!a.contains(b.pinned)&&a.contains(b.unpinned)||(a.add(b.unpinned),a.remove(b.pinned),this.onUnpin&&this.onUnpin.call(this))},pin:function(){var a=this.elem.classList,b=this.classes;a.contains(b.unpinned)&&(a.remove(b.unpinned),a.add(b.pinned),this.onPin&&this.onPin.call(this))},top:function(){var a=this.elem.classList,b=this.classes;a.contains(b.top)||(a.add(b.top),a.remove(b.notTop),this.onTop&&this.onTop.call(this))},notTop:function(){var a=this.elem.classList,b=this.classes;a.contains(b.notTop)||(a.add(b.notTop),a.remove(b.top),this.onNotTop&&this.onNotTop.call(this))},bottom:function(){var a=this.elem.classList,b=this.classes;a.contains(b.bottom)||(a.add(b.bottom),a.remove(b.notBottom),this.onBottom&&this.onBottom.call(this))},notBottom:function(){var a=this.elem.classList,b=this.classes;a.contains(b.notBottom)||(a.add(b.notBottom),a.remove(b.bottom),this.onNotBottom&&this.onNotBottom.call(this))},getScrollY:function(){return void 0!==this.scroller.pageYOffset?this.scroller.pageYOffset:void 0!==this.scroller.scrollTop?this.scroller.scrollTop:(document.documentElement||document.body.parentNode||document.body).scrollTop},getViewportHeight:function(){return window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight},getElementPhysicalHeight:function(a){return Math.max(a.offsetHeight,a.clientHeight)},getScrollerPhysicalHeight:function(){return this.scroller===window||this.scroller===document.body?this.getViewportHeight():this.getElementPhysicalHeight(this.scroller)},getDocumentHeight:function(){var a=document.body,b=document.documentElement;return Math.max(a.scrollHeight,b.scrollHeight,a.offsetHeight,b.offsetHeight,a.clientHeight,b.clientHeight)},getElementHeight:function(a){return Math.max(a.scrollHeight,a.offsetHeight,a.clientHeight)},getScrollerHeight:function(){return this.scroller===window||this.scroller===document.body?this.getDocumentHeight():this.getElementHeight(this.scroller)},isOutOfBounds:function(a){var b=0>a,c=a+this.getScrollerPhysicalHeight()>this.getScrollerHeight();return b||c},toleranceExceeded:function(a,b){return Math.abs(a-this.lastKnownScrollY)>=this.tolerance[b]},shouldUnpin:function(a,b){var c=a>this.lastKnownScrollY,d=a>=this.offset;return c&&d&&b},shouldPin:function(a,b){var c=a<this.lastKnownScrollY,d=a<=this.offset;return c&&b||d},update:function(){var a=this.getScrollY(),b=a>this.lastKnownScrollY?"down":"up",c=this.toleranceExceeded(a,b);this.isOutOfBounds(a)||(a<=this.offset?this.top():this.notTop(),a+this.getViewportHeight()>=this.getScrollerHeight()?this.bottom():this.notBottom(),this.shouldUnpin(a,c)?this.unpin():this.shouldPin(a,c)&&this.pin(),this.lastKnownScrollY=a)}},e.options={tolerance:{up:0,down:0},offset:0,scroller:window,classes:{pinned:"headroom--pinned",unpinned:"headroom--unpinned",top:"headroom--top",notTop:"headroom--not-top",bottom:"headroom--bottom",notBottom:"headroom--not-bottom",initial:"headroom"}},e.cutsTheMustard="undefined"!=typeof f&&f.rAF&&f.bind&&f.classList,e});
var matchHeights = function( elements ) {

	if ( 0 === elements.length ) {
		return;
	}

	var height = 0;

	Array.prototype.forEach.call(elements, function(el, i) {
		if ( height < el.offsetHeight ) {
			height = el.offsetHeight;
		}
	} );

	Array.prototype.forEach.call(elements, function(el, i) {
		el.style.minHeight = height + 'px';
	} );

};
(function($, document, undefined) {

	/**
	 * Global Variables
	 *
	 * @type {*|HTMLElement}
	 */
	var $mobileMenu = $(document.querySelectorAll('ul.js-mobile-sub-menus')),
		$menuOverlay = $(document.querySelector('.menu-overlay-mask'));

	/**
	 * Closes the SubMenu
	 *
	 * @param event
	 */
	function closeSubMenu(event) {
		event.preventDefault();
		$(this).removeClass('is-open').siblings('.sub-menu').removeClass('is-visible');
	}

	/**
	 * Opens the SubMenu
	 *
	 * @param event
	 */
	function openSubMenu(event) {
		event.preventDefault();

		// collapse any other open menus before opening ours.
		$mobileMenu.find('.is-visible').removeClass('is-visible');
		$mobileMenu.find('.is-open').removeClass('is-open');
		$(this).addClass('is-open').siblings('.sub-menu').addClass('is-visible');
	}

	/**
	 * Init Function
	 */
	function init() {
		$mobileMenu.on('click.greaterMedia.Menus', 'a.show-subnavigation', openSubMenu);
		$mobileMenu.on('click.greaterMedia.Menus', 'a.show-subnavigation.is-open', closeSubMenu);
		$menuOverlay.on('click', closeSubMenu);
	}

	init();

})(jQuery, document);
(function ($) {
	// we don't need to use pjax:end event here
	$(document).ready(function() {
		var $onair = $('#on-air'),
			schedule = [],
			fallback = '',
			current_show = {},
			track_schedule, update_onair;

		if ($onair.length === 0) {
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

					top = scroll_top + header_bottom + own_height >= day_bottom ? day_bottom - scroll_top - own_height : header_bottom;

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

(function($, location, document) {
	var $document = $(document),
		classes = {},
		last_url = null,
		current_url = location.href,
		normalize_url,
		siteWrap = $('#site-wrap');

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

	$document.bind( 'pjax:click', function () {
		$( 'body').addClass( 'pjax-start' );
	} );

	/**
	 * Remove the "is-busy" class from the body when a Pjax request ends.
	 */
	$document.bind( 'pjax:end', function () {
		$( 'body').removeClass( 'is-busy' );
	} );

	/**
	 * Adds `pjax--active` class to the `#site-wrap` element when a Pjax request starts. This class can be used for
	 * visual display when Pjax is active.
	 */
	$document.bind('pjax:start', function() {
		siteWrap.addClass('pjax--active');
	});
})(jQuery, location, document);
(function ($, window, document, undefined) {

	/**
	 * Global variables
	 */
	var body = document.querySelector('body');
	var html = document.querySelector('html');
	var header = document.getElementById('header');
	var footer = document.querySelector('.footer');
	var $table = $('table');
	var $tableTd = $('table td');

	/**
	 * Adds a class to a HTML table to make the table responsive
	 */
	function responsiveTables() {
		$table.addClass('responsive');
		$tableTd.removeAttr('width');
	}

	/**
	 * Function to add pop-up for social links
	 *
	 * @returns {boolean}
	 */
	function socialPopup() {
		var href = $(this).attr('href'),
			x = screen.width / 2 - 700 / 2,
			y = screen.height / 2 - 450 / 2;

		window.open(href, href, 'height=485,width=700,scrollbars=yes,resizable=yes,left=' + x + ',top=' + y);

		return false;
	}

	/**
	 * Toggles a target element for contest rules
	 *
	 * @param {MouseEvent} e
	 * @returns {boolean}
	 */
	function contestRulesToggle(e) {
		var target = $($(this).attr('data-target')).get(0),
			currentText = $(this).html(),
			newText = $(this).attr('data-alt-text');

		target.style.display = target.style.display !== 'none' ? 'none' : 'block';

		$(this).html(newText);
		$(this).attr('data-alt-text', currentText);

		return false;
	}

	/**
	 * Personality Toggle
	 */
	function personality_toggle() {
		var $button = $('.person-toggle'),
			start = $('.personality__meta').first().height(); // get the height of the meta before we start, basically tells us whether we're using the mobile or desktop height

		$button.on('click', function (e) {
			var $this = $(this),
				$parent = $this.parent().parent('.personality'),
				$meta = $this.siblings('.personality__meta'),
				curr = $meta.height(),
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

	/**
	 * Init Functions
	 */
	responsiveTables();

	/**
	 * Functions called on Document Ready
	 */
	$(document).ready(function() {
		personality_toggle();
		$('.article__content').fitVids({customSelector: "div[id^='playerwrapper']"});
	});

	/**
	 * Functions specific to document events
	 */
	$(document).on('click', '.popup', socialPopup);

	$(document).on('click', '*[data-toggle="collapse"]', contestRulesToggle);

	/**
	 * Functions that run after the pjax:end event
	 */
	$(document).bind( 'pjax:end', function () {
		personality_toggle();
	});

})(jQuery, window, document);
(function ($, document) {
	var __ready, reset_page = true, pagenums = {};

	__ready = function() {
		$('.posts-pagination--load-more').each(function () {
			var $button = $(this),
				loading = false,
				page_link_template = $button.data('page-link-template'),
				page = parseInt($button.data('page')),
				partial_slug = $button.data('partial-slug'),
				partial_name = $button.data('partial-name'),
				auto_load = $button.data('auto-load');
	
			if (reset_page) {
				pagenums[page_link_template] = !isNaN(page) && page > 0 ? page : 1;
			}

			// If auto_load is set, create a Waypoint that will trigger the button
			// when it is reached. 
			var waypoint_context = null; 
			if ( auto_load ) {
				try {					
					$button.waypoint({
						handler: function(direction) {
							// Store the Waypoint context so we can refresh it later.
							waypoint_context = this.context; 
						$button.trigger('click'); 
						},
						offset: 'bottom-in-view'
					});
				} catch ( e ) {
					// Waypoints not supported, disable autoload. 
					auto_load = false; 
				}
			}

			var hide_button = function() {
				$button.hide();
				if (waypoint_context) {
					waypoint_context.destroy();
				}
			};
	
			$button.click(function() {
				var $self = $(this);
	
				if (!loading) {
					loading = true;
					$self.removeClass('is-loaded');

					// let's use ?ajax=1 to distinguish AJAX and non AJAX requests
					// if we don't do it and HTTP cache is enabled, then we might encounter
					// unpleasant condition when users see cached version of a page loaded by AJAX
					// instead of normal one.
					$.get(page_link_template.replace('%d', pagenums[page_link_template]), {ajax: 1, partial_slug: partial_slug, partial_name: partial_name }).done(function(response) {
						// We're done loading now.
						loading = false;
						$self.addClass('is-loaded');
						
						if ( response.post_count ) {
							// Add content to page. 
							$($('<div>' + $.trim(response.html) + '</div>').html()).insertBefore($button.parents('.posts-pagination'));							

							// Call Analytics
							if ( typeof window.ga === 'function' ) {
								var page = page_link_template.replace( '%d', pagenums[page_link_template] ).replace( location.href, '/' );
								window.ga( 'send', 'pageview', page );
							}

							// Increment page number
							pagenums[page_link_template]++;
							
							// Trigger event.
							$( document ).trigger( 'gmr_lazy_load_end' ); 
						}
												
						if ( ! response.post_count || pagenums[page_link_template] > response.max_num_pages ) {
							hide_button();
						} else if ( waypoint_context ) {
							// Refresh Waypoint context, if any.
							waypoint_context.refresh(); 
						}
					}).fail(function() {
						hide_button();
					});
				}
				
				return false;
			});
		}); 
	};

	if (tribe_ev && tribe_ev.events) {
		$(tribe_ev.events).bind('tribe_ev_ajaxSuccess', __ready);
	}

	$(document).bind('pjax:end', function(e, xhr) {
		reset_page = xhr !== null;
		__ready();
	});

	$(document).ready(__ready);
})(jQuery, document);

(function ($, window, document, undefined) {

	/**
	 * Global variables
	 */
	var html = document.querySelector( 'html' ),
		body = document.querySelector( 'body' ),
		mobileNavButton = document.querySelector( '.mobile-nav__toggle' ),
		mobileMenu = document.getElementById( 'mobile-nav' ),
		header = document.getElementById( 'header' ),
		search = document.getElementById( 'header__search--form' );

	/**
	 * Function to detect if the current browser can use `addEventListener`, if not, use `attachEvent`
	 * this is a specific fix for IE8
	 *
	 * @param elem
	 * @param eventType
	 * @param handler
	 */
	function addEventHandler(elem, eventType, handler) {
		if (elem.addEventListener) {
			elem.addEventListener(eventType, handler, false);
		} else if (elem.attachEvent) {
			elem.attachEvent('on' + eventType, handler);
		}
	}

	/**
	 * Sets the mobile menu just below the header and search
	 */
	function setMenuTop() {
		var headerHeight = header.offsetHeight,
			searchHeight = search.offsetHeight,
			htmlTopMargin = parseInt( window.getComputedStyle( html )['marginTop'], 10 );

		mobileMenu.style.top = headerHeight + searchHeight + htmlTopMargin + 'px';
	}

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	function toggleNavButton() {
		body.classList.toggle('mobile-nav--open');
		setMenuTop();
	}

	/**
	 * Adds a overlay to the body when a menu item that has a sub-menu, is hovered
	 */
	function init_menu_overlay() {
		var $menu = jQuery(document.querySelector('.header__nav--list')),
			$secondary = jQuery(document.querySelector('.header__secondary')),
			$overlay = jQuery(document.querySelector('.menu-overlay-mask')),
			$body = jQuery(document.querySelector('body')),
			$logo = jQuery(document.querySelector('.header__logo'));

		$menu.on('mouseover', '.menu-item-has-children', function (e) {
			$overlay.addClass('is-visible');
			if($body.hasClass('news-site')) {
				$logo.addClass('is-visible');
			}
		});
		$menu.on('mouseout', '.menu-item-has-children', function (e) {
			$overlay.removeClass('is-visible');
			if($body.hasClass('news-site')) {
				$logo.removeClass('is-visible');
			}
		});
	}

	/**
	 * Helps with hover issues on mobile
	 */
	function addHoverMobile() {
		$('.header__nav ul li').on('click touchstart', function() {
			$(this).addClass('active');
		});
	}

	/**
	 * Removes the active class added by addHoverMobile
	 */
	function removeHoverMobile() {
		$('.header__nav ul li').removeClass('active');
	}

	/**
	 * Triggered by pjax to remove the `is-visible` class when the pjax:end event is triggered
	 */
	function removeoverlay() {
		var $overlay = jQuery(document.querySelector('.menu-overlay-mask'));

		$overlay.removeClass('is-visible');
	}

	/**
	 * Adds an active class to a menu item when it is hovered
	 */
	function addMenuHover() {
		$('.header__nav ul li').hover(
			function () {
				$(this).addClass('active');
			},
			function () {
				$(this).removeClass('active');
			}
		);
	}

	/**
	 * Adds an visibility class to the logo when the menu is hovered
	 */
	function addLogoVisibility() {
		var $body = jQuery(document.querySelector('body')),
			$logo = jQuery(document.querySelector('.header__logo')),
			$headerMain = jQuery(document.querySelector('.header__main'));

		$headerMain.on('mouseover', function(e) {
			if ($body.hasClass('news-site')) {
				$logo.addClass('is-visible');
			}
		});

		$headerMain.on('mouseout', function(e) {
			if ($body.hasClass('news-site')) {
				$logo.removeClass('is-visible');
			}
		});
	}

	/**
	 * Init Functions
	 */
	addEventHandler(mobileNavButton, 'click', toggleNavButton);
	init_menu_overlay();
	addHoverMobile();
	addMenuHover();
	addLogoVisibility();

	/**
	 * Functions that run after the pjax:end event
	 */
	$(document).bind( 'pjax:end', function () {
		removeHoverMobile();
		removeoverlay();
	});

})(jQuery, window, document);

(function ($) {
	var __ready = function() {
		$('.home__featured_hero_slider .slideshow').each(function() {
			var $this = $(this);

			$('.featured__article--image', $this).width($this.width());
			$('.feature-post-slide', $this).show();

			$this.cycle({
				timeout: 5000,
				prev: '.slick-prev',
				next: '.slick-next',
				slides: '> .feature-post-slide',
				autoHeight: 'container',
				pager: '.slick-dots'
			});
		});
	};

	$(document).bind('pjax:end', __ready).ready(__ready);

	$(window).resize(_.debounce(function() {
		$('.home__featured_hero_slider .slideshow').each(function() {
			var $this = $(this);
			$('.featured__article--image', $this).width($this.width());
		});
	}, 200));
})(jQuery);
(function($, window, document, undefined) {

	var $searchContainer = $( '#header__search--form '),
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

		$overlay.addClass( 'is-visible' );

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

})(jQuery, window, document);
(function( window, document, undefined ) {

	/**
	 * Variables
	 */
	var body = document.querySelector( 'body' ),
		header = document.getElementById( 'header' ),
		header_main = header.getElementsByClassName( 'header__main' )[0],
		header_container = header.querySelectorAll( '.header__main > .container' )[0];

	if ( window.matchMedia( '(min-width: 768px)' ).matches ) {

		// Using window.onload because heights we want need to be rendered.
		window.addEventListener( 'load', function( e ) {

			// Set variables after things are loaded
			var header_container_height_full = header_container.offsetHeight,
				offset_height = header_container_height_full,
				header_height = header.offsetHeight;

			// Set header height
			header.style.height = header_height + 'px';

			// Manage the position of the header during headroom.js events
			var headroomManagePosition = function() {
				header_main.style.transform = 'translateY(-' + header_container.offsetHeight + 'px)';
			};

			// Headroom.js
			/* jshint ignore:start */
			var headroom_header = new Headroom( header_main, {
				'offset': offset_height,
				'tolerance': 5,
				onPin: function() {
					header_main.style.transform = 'translateY(0)';
				},
				onUnpin: function() {
					headroomManagePosition();
				},
				onTop: function() {
					header_main.style.transform = 'translateY(0)';
					header_main.classList.remove( 'headroom--pinned' ); // This removes the animation on first pin
				},
				onNotTop: function() {
					headroomManagePosition();
				}
			} );
			headroom_header.init();
			/* jshint ignore:end */

		} );
	} else {

		/* jshint ignore:start */
		var headroom_header = new Headroom( header_main, {
			'offset': 0,
			'tolerance': 5
		} );
		headroom_header.init();
		/* jshint ignore:end */

	}

})( window, document );
(function( window, document ) {
	/**
	 * Variables
	 */
	var elements = document.querySelectorAll( 'section.content, aside.sidebar' );

	if ( window.matchMedia( '(min-width: 768px)' ).matches ) {

		/* jshint ignore:start */

		// Using window.onload because heights we want need to be rendered.
		window.addEventListener( 'load', function( e ) {
			matchHeights( elements );
		} );

		// Match heights any time an ad renders.

		// Commented out by Steve as this was throwing a JS error
		/*googletag.pubads().addEventListener( 'slotRenderEnded', function( e ) {
			matchHeights( elements );
		} );*/

		// Not ideal: Match heights every 3 seconds for additional content loading.
		setInterval( function() {
			matchHeights( elements );
		}, 3000 );

		/* jshint ignore:end */
	}

})( window, document );

(function($, config) {
	var auth;

	// do nothing if apiKey is not set
	if (!config.apiKey || $.trim(config.apiKey).length < 1) {
		return;
	}

	// initialize a firebase instance
	firebase.initializeApp(config);

	// grab auth service
	auth = firebase.auth();

	// listen to auth state change and authenticate an user anonymously if user is not logged in
	auth.onAuthStateChanged(function(user) {
		if (!user) {
			auth.signInAnonymously();
		}
	});
})(jQuery, window.platformConfig.firebase);
