<?php
/*
 * This file is a part of the Radium Framework core.
 * Please be cautious editing this file,
 *
 * @package  Radium\Extensions\Video-Central-Addon
 * @package  NewsFront
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 */

// We hook into 'radium_before' because it is the most reliable hook
// available to bbPress in the Radium page load process.
add_action( 'radium_before', 'radium_bbpress_post_actions' );
/**
 * Tweak problematic Radium post actions
 *
 * @since 1.0.0
 */
function radium_bbpress_post_actions() {

    if ( is_bbpress() ) {

        remove_action( 'radium_loop', 'radium_do_loop' );
        add_action( 'radium_loop', 'radium_do_bbpress_loop' );

        // Remove the default Radium sidebar
        remove_action( 'radium_sidebar', 'radium_do_sidebar'     );

        // Load up the Radium-bbPress sidebar
        add_action( 'radium_sidebar', 'radium_bbpress_load_forum_sidebar' );

        /**
         * Remove Radium post content
         *
         * bbPress heavily relies on the_content() so if radium is
         * modifying it unexpectedly, we need to un-unexpect it.
         */
        remove_action( 'radium_post_content',  'radium_do_post_content'   );
        remove_action( 'radium_entry_content', 'radium_do_post_content'   );

        /**
         * Remove the navigation
         *
         * Make sure the Radium navigation doesn't try to show after the loop.
         */
        remove_action( 'radium_after_endwhile', 'radium_posts_nav' );

        /** Add Actions ***************************************************/

        /**
         * Re-add the_content back
         *
         * bbPress doesn't play nice with the Radium formatted content, so
         * we remove it above and reapply the normal version bbPress expects.
         */
        add_action( 'radium_post_content',  'the_content' );
        add_action( 'radium_entry_content', 'the_content' );

        /** Filters *******************************************************/

        /**
         * Remove forum/topic descriptions
         *
         * Many people, myself included, are not a fan of the bbPress
         * descriptions, e.g. "This forum contains 2 topics and 4 replies".
         * So we provided an simple option in the settings to remove them.
         */
        if ( radium_get_option( 'bbp_forum_desc' ) ) {
            add_filter( 'bbp_get_single_forum_description', '__return_false' );
            add_filter( 'bbp_get_single_topic_description', '__return_false' );
        }
    }
}

add_filter( 'radium_site_layout_pre', 'radium_bbress_layout' );
/**
 * Radium bbPress layout control
 *
 * If you set a specific layout for a forum, that will be used for that forum
 * and it's topics. If you set one in the Radium-bbPress setting, that gets
 * checked next. Otherwise bbPress will display itself in Radium default layout.
 *
 * @since 1.0.0
 * @param string $layout
 * @return bool layout to use
 */
function radium_bbress_layout( $layout ) {

    if ( ! is_bbpress() )
        return $layout;

    // Set some defaults
    $forum_id = bbp_get_forum_id();

    $layout = radium_get_option( 'content_sidebar_layout' );
    $parent = false;

    // Check and see if a layout has been set for the parent forum
    if ( ! empty( $forum_id ) ) {
        $parent = esc_attr( get_post_meta( $forum_id, '_radium_layout' , true ) );

        if ( ! empty( $parent ) ) {
            return apply_filters( 'bbp_radium_layout', $parent );
        }
    }

    // Second, see if a layout has been defined in the theme's bbPress settings
    if ( empty( $parent ) ) {
        $layout = radium_get_option( 'bbp_forum_layout', false, 'content-sidebar' );
    }

    // Filter the return value
    return apply_filters( 'bbp_radium_layout', $layout, $forum_id, $parent );
}
