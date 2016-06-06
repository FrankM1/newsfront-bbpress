<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

	<?php do_action( 'bbp_template_before_user_details' ); ?>

	<div class="user-avatar clearfix">
		<?php echo get_avatar( bbp_get_displayed_user_field( 'user_email' ), 80 ); ?>
	</div><!-- end #author-avatar -->

	<div class="user-info">

		<?php
        $user_role = null;

		$displayed_user = get_user_by( 'email', bbp_get_displayed_user_field( 'user_email' ) ); ?>

		<?php if( bbp_get_displayed_user_field( 'description' ) ) {
			$bio_title = bbp_is_user_home_edit() ? sprintf( __( 'Your bio', 'newsfront-bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ) : sprintf( __( 'Bio', 'newsfront-bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>
			<h4><?php echo $bio_title; ?></h4>
			<p><?php echo bbp_get_displayed_user_field( 'description' ); ?></p>
		<?php } ?>

		<div><strong><?php _e( 'Joined: ', 'newsfront-bbpress' ); ?></strong>
		<?php $joined_ago = bbp_get_time_since( bbp_convert_date( $displayed_user->user_registered ) ); ?>
		<span><?php echo $joined_ago; ?></span></div>

		<div><strong><?php _e( 'Status: ', 'newsfront-bbpress' ); ?></strong>
		<?php

        if ( $displayed_user ) {

    		$user_role = array_shift( $displayed_user->roles );

    		if( $user_role == 'bbp_participant' ) {

    			$user_role = __( 'Forum participant', 'newsfront-bbpress' );

    		} elseif( $user_role == 'bbp_moderator' ) {

    			$user_role = __( 'Forum moderator', 'newsfront-bbpress' );

    		}

        }

        ?>
		<span class="user-status"><?php echo $user_role; ?></span></div>

		<?php
		if ( $displayed_user ) $user_url = $displayed_user->user_url;
		if( !empty( $user_url ) ) :
		?>
			<div><strong><?php _e( 'Homepage: ', 'newsfront-bbpress' ); ?></strong>
			<p><a href="<?php echo $user_url; ?>"><?php echo $user_url; ?></a></p></div>
		<?php endif; ?>

	</div><!-- end .user-info -->

	<?php do_action( 'bbp_template_after_user_details' ); ?>