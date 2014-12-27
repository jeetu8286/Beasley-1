<li class="contest-submission">
	<a class="contest-submission--link" href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail(); ?>

		<span class="contest-submission--author"><?php
			$username = 'guest';
			if ( function_exists( 'get_gigya_user_profile' ) && ( $gigya_uid = get_post_meta( get_the_ID(), 'gigya_user_id', true ) ) ) :
				$profile = get_gigya_user_profile( $gigya_uid );
				if ( ! empty( $profile ) ) :
					$profile = filter_var_array( $profile, array(
						'firstName' => FILTER_DEFAULT,
						'lastName'  => FILTER_DEFAULT,
					) );

					$username = "{$profile['firstName']} {$profile['lastName']}";
				endif;
			endif;

			echo esc_html( $username );
		?></span>

		<span class="contest-submission--date"><?php echo esc_html( get_the_date() ); ?></span>
	</a>
</li>