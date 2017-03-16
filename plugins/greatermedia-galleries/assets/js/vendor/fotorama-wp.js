(function ($) {
	$(function () {
		$.fn.fotoramaWPAdapter = function () {
		    this.each(function () {
		        var $this = $(this),
		        	data = $this.data(),
		        	$fotorama = $('<div></div>');

		        $('dl', this).each(function () {
		            var $a = $('dt a', this);
		            $fotorama.append(
		            	$a.attr('data-caption', $('dd', this).html())
		            );
		        });

		        $this.html($fotorama.html());
		    });

		    return this;
		};

		$('.fotorama--wp')
			.fotoramaWPAdapter()
			.fotorama();

		$('.fotorama').on('fotorama:show fotorama:ready', function(e, fotorama, extra){
			if ( fotorama && fotorama.activeFrame && ( true === fotorama.activeFrame.enabledownload ) ) {
				$(this).removeClass('disable-download');
			} else {
				$(this).addClass('disable-download');
			}
		});
	});
})(jQuery);

fotoramaDefaults = {
	nav: 'thumbs',
	allowfullscreen: 'native',
	transition: 'crossfade',
	loop: true,
	keyboard: true,
	hash: true
}

$