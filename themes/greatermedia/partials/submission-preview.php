<section class="col__inner--left">
	<?php the_content(); ?>
</section>
<section class="col__inner--right"><?php 
	$contest_entry_id = get_post_meta( get_the_ID(), 'contest_entry_id', true );
	if ( $contest_entry_id && class_exists( 'GreaterMediaFormbuilderRender', false ) ) :
		$fields = GreaterMediaFormbuilderRender::parse_entry( get_post()->post_parent, $contest_entry_id );
		if ( ! empty( $fields ) ) :
			?><dl class="contest-submission--entries"><?php
				foreach ( $fields as $label => $value ) :
					?><dt><?php echo esc_html( $label ); ?></dt>
					<dd><?php echo esc_html( $value ); ?></dd><?php
				endforeach;
			?></dl><?php
		endif;
	endif;
?></section>