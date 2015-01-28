<?php

namespace GreaterMedia\AdCodeManager;

add_action( 'wp_head', __NAMESPACE__ . '\wp_head', 10000 );
add_action( 'wp_footer', __NAMESPACE__ . '\load_js' );
add_filter( 'acm_output_html', __NAMESPACE__ . '\render_tag', 15, 2 );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_scripts' );

function load_scripts() {
	wp_enqueue_script( 'jquery' ); 
}

function wp_head() {
	// Creates global ad object for use later in the rendering process
	?>
	<script type="text/javascript">
		(function() {
			var GMRAds = GMRAds || {};

			if ( typeof window.innerWidth !== "undefined" ) {
				// Normal Browsers (Including IE9+)
				GMRAds.width = window.innerWidth;
				GMRAds.height = window.innerHeight;
			} else if ( typeof document.documentElement !== "undefined" && typeof document.documentElement.clientWidth !== 0 ) {
				// Old IE Versions
				GMRAds.width = document.documentElement.clientWidth;
				GMRAds.height = document.documentElement.clientHeight;
			} else {
				// Ancient IE Versions
				GMRAds.width = document.getElementsByTagName('body')[0].clientWidth;
				GMRAds.height = document.getElementsByTagName('body')[0].clientHeight;
			}

			window.GMRAds = GMRAds;
		})();
		
		function fill_ad( $slot ) {
			var minWidthOk = true,
				maxWidthOk = true; 
			
			if ( $slot.data( 'min-width' ) ) {
				minWidthOk = ( parseInt( $slot.data( 'min-width' ), 10 ) <= parseInt( GMRAds.width, 10 ) ) ? true : false;
			}
			if ( $slot.data( 'max-width' ) ) {
				maxWidthOk = ( parseInt( $slot.data( 'max-width' ), 10 ) >= parseInt( GMRAds.width, 10 ) ) ? true : false;
			}
			
			if ( maxWidthOk && minWidthOk ) {
				var OX_12345 = new OX();
				OX_12345.addAdUnit( '537282048' );
				OX_12345.setAdUnitSlotId( '537282048', $slot.attr( 'id' ) );
				OX_12345.load();
				
				$slot.addClass( 'gmr-ad-filled' ); 
			}
		}
		
		function fill_ads() {
			$( '.gmr-ad' ).not( '.gmr-ad-filled' ).each( function () {
				fill_ad( $( this ) );					
			} );
		}
		
		jQuery( function( $ ) {
			fill_ads(); 
			$( document ).on( 'gmr_fill_ads', fill_ads );
		} );
	</script>
	<?php
}

function load_js() {
	?>
	<script type="text/javascript" src="//ox-d.greatermedia.com/w/1.0/jstag"></script>
	<?php
}

function render_tag( $output_html, $tag_id ) {
	static $random_number;

	$tag_meta = get_ad_tag_meta( $tag_id );

	if ( false === $tag_meta ) {
		return '';
	}

	$variant = trim( ad_variant() );
	
	$min_width = $max_width = false;
	
	if ( ! empty( $variant ) ) {
		// We know this exists, because otherwise render_variant would not have set this value
		$variant_meta = $tag_meta['variants'][ $variant ];

		$variant_id = '_' . $variant;

		$min_width = isset( $variant_meta['min_width'] ) ? $variant_meta['min_width'] : false;
		$max_width = isset( $variant_meta['max_width'] ) ? $variant_meta['max_width'] : false;
	} else {
		$variant_id = '';

		$min_width = isset( $tag_meta['min_width'] ) ? $tag_meta['min_width'] : false;
		$max_width = isset( $tag_meta['max_width'] ) ? $tag_meta['max_width'] : false;
	}

	if ( is_null( $random_number ) ) {
		$random_number =  str_pad( rand( 0, 999999999999999 ), 15, rand( 0, 9 ), STR_PAD_LEFT );
	}
	
	$uniqid = uniqid();
	
	ob_start();

	?>
	<div
		id="%openx_id%_%tag%<?php echo esc_attr( $variant_id ); ?>_<?php echo esc_attr( $uniqid ); ?>"
		class="gmr-ad"
		data-min-width="<?php echo esc_attr( $min_width ); ?>"
		data-max-width="<?php echo esc_attr( $max_width ); ?>"
	    data-openx-id="%openx_id%"
	>
		<noscript>
			<iframe src="//ox-d.greatermedia.com/w/1.0/afr?auid=%openx_id%&cb=<?php echo intval( $random_number ) ?>" frameborder="0" scrolling="no">
				<a href="http://ox-d.greatermedia.com/w/1.0/rc?cs=9ee0446165&cb=<?php echo intval( $random_number ); ?>" >
					<img src="//ox-d.greatermedia.com/w/1.0/ai?auid=%openx_id%&cs=9ee0446165&cb=<?php echo intval( $random_number ); ?>" border="0" alt="">
				</a>
			</iframe>
		</noscript>		
	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

function render_variant( $tag_id, $variant ) {
	$tag_meta = get_ad_tag_meta( $tag_id );

	if ( false === $tag_meta ) {
		return;
	}

	if ( ! isset( $tag_meta['variants'] ) || ! isset( $tag_meta['variants'][ $variant ] ) ) {
		error_log( "Ad Render Error: Trying to render {$tag_id} with undefined variant {$variant}" );
		return;
	}

	// Set our current variant
	ad_variant( $variant );

	// Do the ad slot
	do_action( 'acm_tag', $tag_id );

	// Clear the current variant
	ad_variant( '' );
}
add_action( 'acm_tag_gmr_variant', __NAMESPACE__ . '\render_variant', 10, 2 );
