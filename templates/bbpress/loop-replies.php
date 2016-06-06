<?php

/**
 * Replies Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_replies_loop' ); ?>

<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="forums bbp-replies">

	<li class="bbp-header">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'newsfront-bbpress' ); ?></div><!-- .bbp-reply-author -->

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'newsfront-bbpress' ); ?>

				<?php bbp_user_subscribe_link(); ?>

				<?php bbp_user_favorites_link(); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'newsfront-bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php while ( bbp_replies() ) : bbp_the_reply(); ?>

			<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

		<?php endwhile; ?>

	</li><!-- .bbp-body -->

	<li class="bbp-footer">

		<div class="bbp-reply-author"><?php  _e( 'Author',  'newsfront-bbpress' ); ?></div>

		<div class="bbp-reply-content">

			<?php if ( !bbp_show_lead_topic() ) : ?>

				<?php _e( 'Posts', 'newsfront-bbpress' ); ?>

			<?php else : ?>

				<?php _e( 'Replies', 'newsfront-bbpress' ); ?>

			<?php endif; ?>

		</div><!-- .bbp-reply-content -->

	</li>

</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php do_action( 'bbp_template_after_replies_loop' ); ?>
